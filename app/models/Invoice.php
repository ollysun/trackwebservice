<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class Invoice extends BaseModel
{
    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('invoices');
    }

    /**
     * Generates an invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $data
     * @return bool
     */
    public static function generate($data)
    {
        return self::add($data);
    }

    /**
     * Generates the invoice number
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return string
     */
    public static function generateInvoiceNumber()
    {
        $invoiceNumber = 'TP-' . date('Ymd-His') . '-' . rand(0,10);
        return $invoiceNumber;
    }
}