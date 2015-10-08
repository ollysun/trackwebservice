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

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $params
     * @param $offset
     * @param $count
     * @return array
     */
    public static function getRequests($params, $offset, $count)
    {
        if (!isset($params['conditions'], $params['bind'])) {
            $params['conditions'] = null;
            $params['bind'] = [];
        }

        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('PickupRequest')
            ->columns(['PickupRequest.*', 'PickupState.*', 'PickupCity.*', 'DestinationState.*', 'DestinationCity.*'])
            ->innerJoin('State', 'PickupState.id = PickupRequest.pickup_state_id', 'PickupState')
            ->innerJoin('City', 'PickupCity.id = PickupRequest.pickup_city_id', 'PickupCity')
            ->innerJoin('State', 'DestinationState.id = PickupRequest.destination_state_id', 'DestinationState')
            ->innerJoin('City', 'DestinationCity.id = PickupRequest.destination_city_id', 'DestinationCity')
            ->where($params['conditions'])
            ->limit($count)
            ->offset($offset);

        $result = $builder->getQuery()->execute($params['bind']);

        $requests = [];
        foreach ($result as $data) {
            $request = $data->pickupRequest->toArray();
            $request['pickup_city'] = $data->PickupCity->toArray();
            $request['pickup_state'] = $data->PickupState->toArray();
            $request['destination_city'] = $data->DestinationCity->toArray();
            $request['destination_state'] = $data->DestinationState->toArray();
            $requests[] = $request;
        }

        return $requests;
    }
}