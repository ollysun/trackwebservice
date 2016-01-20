<?php

/**
 * Class BulkShipmentJobDetail
 * @property int job_id
 * @property string data
 * @property string status
 * @property string started_at
 * @property string error_message
 * @property string completed_at
 * @property string waybill_number
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BulkShipmentJobDetail extends BaseModel
{
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function initialize()
    {
        $this->setSource('bulk_shipment_job_details');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $job_id
     * @return array
     */
    public static function getWaybillNumberByJobId($job_id)
    {
        $result = self::find(['conditions' => 'waybill_number IS NOT NULL AND job_id=:job_id: AND status=:success:', 'bind' => ['job_id' => $job_id, 'success' => self::STATUS_SUCCESS], 'columns' => 'waybill_number']);
        return ($result) ? $result->toArray() : [];
    }

}