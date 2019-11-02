<?php
declare(strict_types=1);

namespace Setapp\Test\Payments;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Payments\InterfacePayment;

class BasePaymentGateway implements InterfacePaymentGateway
{

    /**
     * @param InterfaceInvoice[]|array $invoices
     *
     * @return boolean[] array for results [INVOICEID => RESULT, ...]
     */
    public function charge(array $invoices): array
    {
        $result = [];

        foreach ($invoices as $invoice) {
            $concretePayment = $this->paymentFactory($invoice->getProvider());
            $result[$invoice->getId()] =  $concretePayment->sendInvoice($invoice);
        }

        return $result;
    }

    /**
     * Create concrete payment strategy
     *
     * @param string $provider
     * @return InterfacePayment
     */
    private function paymentFactory(string $provider): InterfacePayment
    {
        $providerName = "Setapp\Test\Payments\Payments\\" . ucfirst($provider) . 'Payment';
        return new $providerName();
    }
}
