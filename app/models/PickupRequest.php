<?php
use Phalcon\Mvc\Model;

/**
 * Class CorporatePickupRequest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class PickupRequest extends BaseModel
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
     * @param $offset
     * @param $count
     * @param array $fetch_with
     * @param array $filter_by
     * @return array
     */
    public static function getRequests($offset, $count, $fetch_with = [], $filter_by = [])
    {
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

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder = $builder->columns($columns)->limit($count)->offset($offset);
        $result = $builder->getQuery()->execute();

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
            $builder = Util::betweenDateRange($builder, 'PickupRequest.created_at', $from, $to);
        }

        if (isset($filter_by['status'])) {
            $builder->andWhere('PickupRequest.status=:status:', ['status' => $filter_by['status']], ['status' => PDO::PARAM_STR]);
        }

        if (isset($filter_by['company_id'])) {
            $builder->andWhere('PickupRequest.company_id=:company_id:', ['company_id' => $filter_by['company_id']]);
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
        $builder = $obj->getModelsManager()->createBuilder()->from('PickupRequest');
        $columns = ['COUNT(*) AS count'];
        $builder = self::addFetchCriteria($builder, $filter_by);
        $count = $builder->columns($columns)->getQuery()->getSingleResult();
        return $count['count'];
    }

    /**
     * Gets one pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $id
     * @param array $fetch_with
     * @return array
     */
    public static function getOne($id, $fetch_with = [])
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('PickupRequest');
        $columns = ['PickupRequest.*'];

        if (in_array('with_company', $fetch_with)) {
            $columns[] = 'Company.*';
            $builder = $builder->innerJoin('Company', 'Company.id = PickupRequest.company_id');
        }

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


        if (in_array('with_company', $fetch_with)) {
            $columns[] = 'Company.*';
            $builder = $builder->innerJoin('Company', 'Company.id = PickupRequest.company_id');
        }

        if (in_array('with_created_by', $fetch_with)) {
            $columns[] = 'CompanyUser.*';
            $builder = $builder->innerJoin('CompanyUser', 'CompanyUser.id = PickupRequest.created_by');
        }

        $builder->andWhere('PickupRequest.id = :id:', ['id' => $id]);
        $builder = $builder->columns($columns);
        $result = $builder->getQuery()->execute();

        if (count($result) == 0) {
            return false;
        }

        $request = [];

        if (isset($result[0]->pickupRequest)) {
            $request = $result[0]->pickupRequest->getData();
            if (in_array('with_company', $fetch_with)) {
                $request['company'] = $result[0]->company->getData();
            }
            if (in_array('with_pickup_city', $fetch_with)) {
                $request['pickup_city'] = $result[0]->PickupCity->getData();
            }
            if (in_array('with_pickup_state', $fetch_with)) {
                $request['pickup_state'] = $result[0]->PickupState->getData();
            }
            if (in_array('with_destination_city', $fetch_with)) {
                $request['destination_city'] = $result[0]->DestinationCity->getData();
            }
            if (in_array('with_destination_state', $fetch_with)) {
                $request['destination_state'] = $result[0]->DestinationState->getData();
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
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getData()
    {
        return $this->toArray();
    }
}