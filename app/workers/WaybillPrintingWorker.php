<?php

/**
 * Class WaybillPrintingWorker
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class WaybillPrintingWorker extends BaseWorker
{
    const QUEUE_BULK_WAYBILL_PRINTING = 'bulk_waybill_printing';

    public function __construct()
    {
        parent::__construct(self::QUEUE_BULK_WAYBILL_PRINTING);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return BaseJob
     */
    public function initializeJob()
    {
        return new BulkWaybillPrintingJob($this->getCurrentJob());
    }
}