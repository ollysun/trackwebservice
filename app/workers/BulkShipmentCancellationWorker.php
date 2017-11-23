<?php
/**
 * Created by PhpStorm.
 * User: Moses Olalere
 * Date: 11/13/2017
 * @author Moses olalere <moses_olalere@superfluxnigeria.com>
 * Time: 6:41 PM
 */

class BulkShipmentCancellationWorker extends BaseWorker
{
    const QUEUE_BULK_SHIPMENT_CANCELLATION = 'bulk_shipment_cancellation';

    public function __construct()
    {
        parent::__construct(self::QUEUE_BULK_SHIPMENT_CANCELLATION);
    }

    public function initializeJob()
    {
        return new BulkShipmentCancellationJob($this->getCurrentJob());
    }
}