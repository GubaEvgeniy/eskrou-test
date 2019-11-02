<?php


namespace Setapp\Test\Payments\Payments;

use Setapp\Test\Core\InterfaceInvoice;
use Setapp\Test\Payments\Providers\DetailedProvider;

class DetailedPayment implements InterfacePayment
{
    /**
     * @param InterfaceInvoice $invoice
     * @return bool
     */
    public function sendInvoice(InterfaceInvoice $invoice): bool
    {
        $provider = new DetailedProvider();

        date_default_timezone_set('Europe/Kiev');

        $data = [
            'amount' => $invoice->getAmount(),
            'request_time' => date(DATE_ATOM),
            'invoice_id' => $invoice->getId()
        ];

        $provider->schedule($invoice->getCustomerId(), $data);

        return true;
    }
}