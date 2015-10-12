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

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $params
     * @param $offset
     * @param $count
     * @param array $fetch_with
     * @return mixed
     */
    public static function getRequests($params, $offset, $count, $fetch_with = [])
    {
        if (!isset($params['conditions'], $params['bind'])) {
            $params['conditions'] = null;
            $params['bind'] = [];
        }

        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('ShipmentRequest');
        $columns = ['ShipmentRequest.*'];

        if (in_array('with_receiver_state', $fetch_with)) {
            $columns[] = 'State.*';
            $builder = $builder->innerJoin('State', 'State.id = ShipmentRequest.receiver_state_id');
        }

        if (in_array('with_receiver_city', $fetch_with)) {
            $columns[] = 'City.*';
            $builder = $builder->innerJoin('City', 'City.id = ShipmentRequest.receiver_city_id');
        }

        $builder = $builder->columns($columns)->where($params['conditions'])
            ->limit($count)
            ->offset($offset);

        $result = $builder->getQuery()->execute($params['bind']);

        $requests = [];
        foreach ($result as $data) {
            $request = (property_exists($data, 'shipmentRequest')) ? $data->shipmentRequest->toArray() : $data->toArray();
            if (in_array('with_receiver_city', $fetch_with)) {
                $request['receiver_city'] = $data->city->toArray();
            }
            if (in_array('with_receiver_state', $fetch_with)) {
                $request['receiver_state'] = $data->state->toArray();
            }
            $requests[] = $request;
        }

        return $requests;
    }
}