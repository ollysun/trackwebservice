<?php

/**
 * Class ParcelCreationWorker
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ParcelCreationWorker extends BaseWorker
{
    const QUEUE_BULK_SHIPMENT_CREATION = 'bulk_shipment_creation';

    public function __construct()
    {
        parent::__construct(self::QUEUE_BULK_SHIPMENT_CREATION);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return BaseJob
     */
    public function initializeJob()
    {
        return new BulkParcelCreationJob($this->getCurrentJob());
    }
}