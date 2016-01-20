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
     * Add job to queue
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $jobData
     * @return int job_id | false
     */
    public function addJob($jobData)
    {
        $jobId = parent::addJob($jobData);
        if (!$jobId) {
            return false;
        }

        $bulkShipmentJob = new Job();
        $bulkShipmentJob->server_job_id = $jobId;
        $bulkShipmentJob->queue = $this->queue;
        $bulkShipmentJob->job_data = $jobData;
        $jobData = json_decode($jobData);
        $bulkShipmentJob->created_by = $jobData->created_by;
        $bulkShipmentJob->status = Job::STATUS_QUEUED;
        $bulkShipmentJob->created_at = Util::getCurrentDateTime();
        return ($bulkShipmentJob->save()) ? $bulkShipmentJob->id : false;
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