<?php
use Phalcon\Mvc\Model;

/**
 * Class ShipmentRequest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ShipmentRequest extends Model
{
    const STATUS_PENDING = 'pending';


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('shipment_requests');
    }

    /**
     * Add a new corporate shipment
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return bool|ShipmentRequest
     */
    public static function add($data)
    {
        $data = (array)$data;
        $shipmentRequest = new self();

        foreach ($data as $key => $value) {
            $shipmentRequest->$key = $value;
        }

        if ($shipmentRequest->save()) {
            return $shipmentRequest->toArray();
        } else {
            return false;
        }
    }
}