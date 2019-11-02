<?php


namespace Setapp\Test\Payments\Payments;


use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\LambdaProvider;

class LambdaPayment implements InterfacePayment
{
    /**
     * @param InterfaceInvoice $invoice
     * @return bool
     */
    public function sendInvoice(InterfaceInvoice $invoice): bool
    {
        $provider = new LambdaProvider();

        $lambdaInvoice['invoices'] = [
            $invoice->getId() => [$invoice->getCustomerId(), $invoice->getAmount()]
        ];

        $result = $provider->charge($lambdaInvoice);

        return $result[$invoice->getId()];
    }
}