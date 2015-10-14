<?php
use Phalcon\Mvc\Model;

/**
 * Class ShipmentRequest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ShipmentRequest extends BaseModel
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
     * @param $offset
     * @param $count
     * @param array $fetch_with
     * @param array $filter_by
     * @return mixed
     */
    public static function getRequests($offset, $count, $fetch_with = [], $filter_by = [])
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('ShipmentRequest');
        $columns = ['ShipmentRequest.*'];

        if (in_array('with_company', $fetch_with)) {
            $columns[] = 'Company.*';
            $builder = $builder->innerJoin('Company', 'Company.id = ShipmentRequest.company_id');
        }

        if (in_array('with_receiver_state', $fetch_with)) {
            $columns[] = 'State.*';
            $builder = $builder->innerJoin('State', 'State.id = ShipmentRequest.receiver_state_id');
        }

        if (in_array('with_receiver_city', $fetch_with)) {
            $columns[] = 'City.*';
            $builder = $builder->innerJoin('City', 'City.id = ShipmentRequest.receiver_city_id');
        }

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder = $builder->columns($columns)->limit($count)->offset($offset);
        $result = $builder->getQuery()->execute();

        $requests = [];
        foreach ($result as $data) {
            $request = (property_exists($data, 'shipmentRequest')) ? $data->shipmentRequest->toArray() : $data->toArray();
            if (in_array('with_company', $fetch_with)) {
                $request['company'] = $data->company->toArray();
            }
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

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $id
     * @param array $fetch_with
     * @return array|bool
     */
    public static function getOne($id, $fetch_with = [])
    {
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

        if (in_array('with_company', $fetch_with)) {
            $columns[] = 'Company.*';
            $builder = $builder->innerJoin('Company', 'Company.id = ShipmentRequest.company_id');
        }

        if (in_array('with_created_by', $fetch_with)) {
            $columns[] = 'CompanyUser.*';
            $builder = $builder->innerJoin('CompanyUser', 'CompanyUser.id = ShipmentRequest.created_by');
        }

        $builder->andWhere('ShipmentRequest.id = :id:', ['id' => $id]);
        $builder = $builder->columns($columns);
        $result = $builder->getQuery()->execute();

        if (count($result) == 0) {
            return false;
        }

        $request = [];

        if (isset($result[0]->shipmentRequest)) {
            $request = $result[0]->shipmentRequest->getData();
            if (in_array('with_receiver_city', $fetch_with)) {
                $request['receiver_city'] = $result[0]->city->getData();
            }
            if (in_array('with_receiver_state', $fetch_with)) {
                $request['receiver_state'] = $result[0]->state->getData();
            }
            if (in_array('with_company', $fetch_with)) {
                $request['company'] = $result[0]->company->getData();
            }
            if (in_array('with_created_by', $fetch_with)) {
                $request['created_by'] = $result[0]->companyUser->getData();
            }
        } else {
            $request = $result[0]->getData();
        }

        return $request;
    }


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $builder
     * @param $filter_by
     * @return Model\Query\Builder|Model\Query\BuilderInterface
     */
    private static function addFetchCriteria($builder, $filter_by)
    {
        if (isset($filter_by['from_created_at']) || $filter_by['to_created_at']) {
            $from = (isset($filter_by['from_created_at'])) ? $filter_by['from_created_at'] . ' 00:00:00' : null;
            $to = (isset($filter_by['to_created_at'])) ? $filter_by['to_created_at'] . ' 23:59:59' : null;
            $builder = Util::betweenDateRange($builder, 'ShipmentRequest.created_at', $from, $to);
        }

        if (isset($filter_by['status'])) {
            $builder->andWhere('ShipmentRequest.status=:status:', ['status' => $filter_by['status']], ['status' => PDO::PARAM_STR]);
        }

        if (isset($filter_by['company_id'])) {
            $builder->andWhere('ShipmentRequest.company_id=:company_id:', ['company_id' => $filter_by['company_id']]);
        }

        return $builder;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $filter_by
     */
    public static function getTotalCount($filter_by)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()->from('ShipmentRequest');
        $columns = ['COUNT(*) as count'];
        $builder = self::addFetchCriteria($builder, $filter_by);
        $count = $builder->columns($columns)->getQuery()->getSingleResult();
        return $count['count'];
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getData()
    {
        return $this->toArray();
    }
}