<?php
use Phalcon\Mvc\Model;

/**
 * Class CorporatePickupRequest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class PickupRequest extends EagerModel
{
    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';
    const STATUS_APPROVED = 'approved';

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

        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder = $builder->columns($columns)->limit($count)->offset($offset);
        $result = $builder->getQuery()->execute();

        $requests = [];
        foreach ($result as $data) {
            $request = (property_exists($data, 'pickupRequest')) ? $data->pickupRequest->toArray() : $data->toArray();
            $relatedRecords = $obj->loadRelatedModels($data, true);
            $request = array_merge($request, $relatedRecords);
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

        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder->andWhere('PickupRequest.id = :id:', ['id' => $id]);
        $builder = $builder->columns($columns);
        $result = $builder->getQuery()->execute();

        if (count($result) == 0) {
            return false;
        }

        $request = [];

        if (isset($result[0]->pickupRequest)) {
            $request = $result[0]->pickupRequest->getData();

            $relatedRecords = $obj->loadRelatedModels($result);
            $request = array_merge($request, $relatedRecords);
        } else {
            $request = $result[0]->getData();
        }

        return $request;
    }

    /**
     * Links a parcel to the pickup request and changes the status of the request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $pickupRequestId
     * @param $parcelId
     * @return bool
     */
    public static function linkParcelAndChangeStatus($pickupRequestId, $parcelId)
    {
        $pickupRequest = PickupRequest::findFirst($pickupRequestId);
        if(!$pickupRequestId) {
            return false;
        }

        $pickupRequest->status = PickupRequest::STATUS_APPROVED;
        $pickupRequest->parcel_id = $parcelId;

        return $pickupRequest->save();
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getData()
    {
        return $this->toArray();
    }


    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [
            [
                'field' => 'company',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'Company',
                'foreign_key' => 'company_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'pickup_city',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'City',
                'foreign_key' => 'pickup_city_id',
                'reference_key' => 'id',
                'alias' => 'PickupCity'
            ],
            [
                'field' => 'pickup_state',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'State',
                'foreign_key' => 'pickup_state_id',
                'reference_key' => 'id',
                'alias' => 'PickupState'
            ],
            [
                'field' => 'parcel',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'Parcel',
                'foreign_key' => 'parcel_id',
                'reference_key' => 'id',
                'join_type' => 'left'
            ],
            [
                'field' => 'destination_city',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'City',
                'foreign_key' => 'destination_city_id',
                'reference_key' => 'id',
                'alias' => 'DestinationCity'
            ],
            [
                'field' => 'destination_state',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'State',
                'foreign_key' => 'destination_state_id',
                'reference_key' => 'id',
                'alias' => 'DestinationState'
            ],
            [
                'field' => 'created_by',
                'model_name' => 'PickupRequest',
                'ref_model_name' => 'CompanyUser',
                'foreign_key' => 'created_by',
                'reference_key' => 'id'
            ],
        ];
    }
}