<?php
/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 10/15/2016
 * Time: 2:10 PM
 */

/**
 * Class ParcelCreationWorker
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ParcelImportWorker extends BaseWorker
{
    const QUEUE_BULK_SHIPMENT_CREATION = 'parcel_import_worker';

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
        return new ParcelImportJob($this->getCurrentJob());
    }
}