<?php
use Phalcon\Mvc\Model;

/**
 * Class CorporatePickupRequest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class PickupRequest extends Model
{
    const STATUS_PENDING = 'pending';


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('pickup_requests');
    }

    /**
     * Add a new corporate shipment
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return bool|PickupRequest
     */
    public static function add($data)
    {
        $pickupRequest = new self();
        self::load($data, $pickupRequest);
        return ($pickupRequest->save()) ? $pickupRequest : false;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @param $model
     * @param string $prefix
     */
    private static function load($data, $model, $prefix = '')
    {
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $prefix .= $key . "_";
                self::load($value, $model, $prefix);
                $prefix = '';
            } else {
                $model->{$prefix . $key} = $value;
            }
        }
    }
}