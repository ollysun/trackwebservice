<?php
use Phalcon\Mvc\Model;

/**
 * Class CorporateShipmentRequest
 * @author Adeyemi Olaoye <yemexx@cottacush.com>
 */
class CorporateShipmentRequest extends Model
{
    const STATUS_PENDING = 'pending';


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('corporate_shipment_requests');
    }

    /**
     * Add a new corporate shipment
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return bool|CorporateShipmentRequest
     */
    public static function add($data)
    {
        $data = (array)$data;
        $corporateShipmentRequest = new self();

        foreach ($data as $key => $value) {
            $corporateShipmentRequest->$key = $value;
        }

        if ($corporateShipmentRequest->save()) {
            return $corporateShipmentRequest->toArray();
        } else {
            return false;
        }
    }
}