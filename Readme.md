### Описание задачи:
Создать гибкую систему для обработки счёт-фактур и оправки их в определённые платёжные системы, которые после обработки переданных данных возвращают какой-либо результат(операция удалась или не состоялась).
Результатом выполнения задачи является `OK` в проведённых тестах

### Описание решения:

На мой взгляд файл `BasePaymentGateway.php` будет представлять из себя так называемую фабрику. Его задача:
1. получить список `$invoice`(счет-фактура)
2. в зависимости от `provider`, который явно указан в счет-фактуре, создать объект конкретного обработчика
3. передать данные в обработчик, после получить и вернуть результат

Обработчик - это по сути стратегия, задача которого иметь возможность на ходу подменить один алгоритм другим. Для этого создал интерфейс `InterfacePayment.php` в котором будет находится всего 1 метод `sendInvoice` который отвечает за обработку счета и отправку его в платёжную систему.

> возможно, для большей читабельности в объектах `DetailedPayment`,`LambdaPayment` и `SimplePayment`, которые реализуют `InterfacePayment.php` должно быть еще пару методов которые разделяют логику: один - на подготовку данных к отправке, второй - к отправке данных, третий - должен обработать возвращаемый результат, вернуть какой-нибудь `Exeption` (т.к. я думаю, что в платёжных системах может вернуться больше, чем оплата удалась/не удалась), но в приведенной задаче логика проста и не требует лишнего. Считаю, что не стоит усложнять.

Такая структура даст нам такие преимущства:
1. Каждый обработчик данных будет реализовывать один интерфейс, что позволит создавать любое количество таких обработчиков
2. Самому `BasePaymentGateway.php` всё равно какое количество платёжных систем подключено. Он и не должен знать, его задача состоит в другом.

### Некоторые заметки по коду

#### `DetailedPayment.php`
В данном классе сразу два момента которые привлекли моё внимание:
1. Реализация `DetailedProvider.php` которая ничего не возвращает обычным путём и к которому у нас доступа нет. Внутри неё находятся всего лишь проверки на утверждения(функция `assert()`). К которой в *обычном* случае можно получить доступ через установку опций используя `assert_options` или редактирую `php.ini`. 
По идее `DetailedProvider.php` который представляет из себя *условную* платёжную систему, обязан возвращать что-либо. Однозначного ответа я не нашёл, поэтому при условии, что в тестах данное решение всегда возвращает `true`, мой класс `DetailedPayment.php`, тоже всегда возвращает `true` по умолчанию(хотя это нелогично в рамках темы финансы. Ну как так платёж и всегда успешен)

2. Отправляемый параметр `request_time` который по условию задачи должен слать `CURRENT_TIME`. Возможно я не до конца разобрался, но для меня осталось неясным чье корректное время: время клиента, который формируется счёт, или время сервера где происходит обработка платёжа. Насколько я понимаю, само понятие время - очень важное в теме финансов. 
В моём решении это моё текущее время с установкой часового пояса в таймзону `'Europe/Kiev'`

#### `BasePaymentGatewayTest.php`

В описании тестового задания есть строчка про необходимость улучшения `BasePaymentGatewayTest.php`. На мой взгляд в данном файле тестов была допущена ошибка. Конкретнее, в возвращаемом результате функции `getChargeTestsSets` для `$lambdaFail1` и `$lambdaFail2`. Из семантического названия становится ясно, что две данные переменные должны нести под собой неудавшиеся проведения оплаты счёта, т.е. попропросту быть `false`. Но в значении `'mixed providers'` они соответствовали `true`, да еще и не под своими ключами - `l-f1`, `l-f2`.