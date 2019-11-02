<?php


namespace Setapp\Test\Payments\Payments;


use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\SimpleProvider;

class SimplePayment implements InterfacePayment
{

    /**
     * @param InterfaceInvoice $invoice
     * @return bool
     */
    public function sendInvoice(InterfaceInvoice $invoice):bool
    {
        $provider = new SimpleProvider();

        $customerId = $invoice->getCustomerId();
        $amount = $invoice->getAmount();

        $return = $provider->charge($customerId, $amount);

        return $return;
    }
}