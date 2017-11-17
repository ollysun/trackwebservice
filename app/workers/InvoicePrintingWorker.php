<?php

/**
 * Created by InvoicePrintingWorker.php.
 * @author Rotimi Akintewe <rotimi.akintewe@konga.com>
 */
class InvoicePrintingWorker extends BaseWorker
{
    const QUEUE_BULK_INVOICE_PRINTING = 'bulk_invoice_printing';

    public function __construct()
    {
        parent::__construct(self::QUEUE_BULK_INVOICE_PRINTING);
    }

    /**
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @return BaseJob
     */
    public function initializeJob()
    {
        return new BulkInvoicePrintingJob($this->getCurrentJob());
    }
}