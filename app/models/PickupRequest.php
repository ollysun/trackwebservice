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
    public static function getRequests($params, $offset, $count, $fetch_with = [])
    {
        if (!isset($params['conditions'], $params['bind'])) {
            $params['conditions'] = null;
            $params['bind'] = [];
        }

        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('PickupRequest');
        $columns = ['PickupRequest.*'];

        if (in_array('with_pickup_state', $fetch_with)) {
            $columns[] = 'PickupState.*';
            $builder = $builder->innerJoin('State', 'PickupState.id = PickupRequest.pickup_state_id', 'PickupState');
        }

        if (in_array('with_pickup_city', $fetch_with)) {
            $columns[] = 'PickupCity.*';
            $builder = $builder->innerJoin('City', 'PickupCity.id = PickupRequest.pickup_city_id', 'PickupCity');
        }

        if (in_array('with_destination_state', $fetch_with)) {
            $columns[] = 'DestinationState.*';
            $builder = $builder->innerJoin('State', 'DestinationState.id = PickupRequest.destination_state_id', 'DestinationState');
        }

        if (in_array('with_destination_city', $fetch_with)) {
            $columns[] = 'DestinationCity.*';
            $builder = $builder->innerJoin('City', 'DestinationCity.id = PickupRequest.destination_city_id', 'DestinationCity');
        }

        $builder = $builder->columns($columns)->where($params['conditions'])->limit($count)->offset($offset);
        $result = $builder->getQuery()->execute($params['bind']);

        $requests = [];
        foreach ($result as $data) {
            $request = (property_exists($data, 'pickupRequest')) ? $data->pickupRequest->toArray() : $data->toArray();

            if (in_array('with_pickup_city', $fetch_with)) {
                $request['pickup_city'] = $data->PickupCity->toArray();
            }

            if (in_array('with_pickup_state', $fetch_with)) {
                $request['pickup_state'] = $data->PickupState->toArray();
            }

            if (in_array('with_destination_city', $fetch_with)) {
                $request['destination_city'] = $data->DestinationCity->toArray();
            }

            if (in_array('with_destination_state', $fetch_with)) {
                $request['destination_state'] = $data->DestinationState->toArray();
            }

            $requests[] = $request;
        }

        return $requests;
    }
}