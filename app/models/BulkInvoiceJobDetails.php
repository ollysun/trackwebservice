<?php

/**
 * Created by BulkInvoiceJobDetails.php.
 * @author Rotimi Akintewe <rotimi.akintewe@konga.com>
 */
class BulkInvoiceJobDetails extends BaseModel
{
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function initialize()
    {
        $this->setSource('bulk_invoice_job_details');
    }
}