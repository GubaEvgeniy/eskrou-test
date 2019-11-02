<?php


namespace Setapp\Test\Payments\Payments;


use Setapp\Test\Core\InterfaceInvoice;


interface InterfacePayment
{
    /**
     * @param InterfaceInvoice $invoice
     * @return bool
     */
    public function sendInvoice(InterfaceInvoice $invoice): bool;
}