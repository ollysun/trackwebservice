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

}