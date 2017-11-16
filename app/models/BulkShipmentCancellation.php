<?php
/**
 * Created by PhpStorm.
 * User: Moses Olalere
 * Date: 11/13/2017
 * @property int job_id
 * @property string data
 * @property string status
 * @property string started_at
 * @property string error_message
 * @property string completed_at
 * @property string waybill_number
 * @author Moses Olalere <moses_olalere@superfluxnigeria.com>
 * Time: 4:13 PM
 */

class BulkShipmentCancellation extends BaseModel
{
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function initialize()
    {
        $this->setSource('bulk_shipment_cancellations');
    }
}