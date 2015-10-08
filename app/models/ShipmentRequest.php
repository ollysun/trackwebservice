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
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param $params
     * @param $offset
     * @param $count
     * @return mixed
     */
    public static function getRequests($params, $offset, $count)
    {
        if (!isset($params['conditions'], $params['bind'])) {
            $params['conditions'] = null;
            $params['bind'] = [];
        }

        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('ShipmentRequest')
            ->columns('ShipmentRequest.*, State.*, City.*')
            ->innerJoin('State', 'State.id = ShipmentRequest.receiver_state_id')
            ->innerJoin('City', 'City.id = ShipmentRequest.receiver_city_id')
            ->where($params['conditions'])
            ->limit($count)
            ->offset($offset);

        $result = $builder->getQuery()->execute($params['bind']);

        $requests = [];
        foreach ($result as $data) {
            $request = $data->shipmentRequest->toArray();
            $request['city'] = $data->city->toArray();
            $request['state'] = $data->state->toArray();
            $requests[] = $request;
        }

        return $requests;
    }
}