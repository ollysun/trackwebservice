<?php

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 14/01/2016
 * Time: 12:28 PM
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

    }
}