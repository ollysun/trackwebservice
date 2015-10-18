<?php
use Phalcon\Mvc\Model;

/**
 * Class ShipmentRequest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ShipmentRequest extends EagerModel implements CorporateRequestStatusInterface
{

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

        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder = $builder->columns($columns)->limit($count)->offset($offset);
        $result = $builder->getQuery()->execute();

        $requests = [];
        foreach ($result as $data) {
            $request = (property_exists($data, 'shipmentRequest')) ? $data->shipmentRequest->toArray() : $data->toArray();

            $relatedRecords = $obj->loadRelatedModels($data, true);
            $request = array_merge($request, $relatedRecords);

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

        if (in_array('with_company_city', $fetch_with) && in_array('with_company', $fetch_with)) {
            $columns[] = 'CompanyCity.*';
            $builder = $builder->innerJoin('City', 'CompanyCity.id = Company.city_id', 'CompanyCity');
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
            if (in_array('with_company_city', $fetch_with)) {
                $request['company_city'] = $result[0]->CompanyCity->getData();
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

        if (isset($filter_by['waybill_number'])) {
            $builder->andWhere('ShipmentRequest.waybill_number=:waybill_number:', ['waybill_number' => $filter_by['waybill_number']]);
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
     * Links a parcel to the shipment request and changes the status of the request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $shipmentRequestId
     * @param $waybillNumber
     * @return bool
     */
    public static function linkParcelAndChangeStatus($shipmentRequestId, $waybillNumber)
    {
        $shipmentRequest = ShipmentRequest::findFirst($shipmentRequestId);
        if(!$shipmentRequestId) {
            return false;
        }

        $shipmentRequest->status = ShipmentRequest::STATUS_APPROVED;
        $shipmentRequest->waybill_number = $waybillNumber;

        return $shipmentRequest->save();
    }

    /**
     * Cancels a shipment request by changing the status to canceled
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return bool
     */
    public function cancelRequest()
    {
        $this->status = ShipmentRequest::STATUS_CANCELED;
        return $this->save();
    }

    /**
     * Declines a shipment request by changing the status to canceled
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return bool
     */
    public function declineRequest()
    {
        $this->status = ShipmentRequest::STATUS_DECLINED;
        return $this->save();
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
                'model_name' => 'ShipmentRequest',
                'ref_model_name' => 'Company',
                'foreign_key' => 'company_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'company_city',
                'model_name' => 'Company',
                'ref_model_name' => 'City',
                'foreign_key' => 'city_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'receiver_state',
                'model_name' => 'ShipmentRequest',
                'ref_model_name' => 'State',
                'foreign_key' => 'receiver_state_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'receiver_city',
                'model_name' => 'ShipmentRequest',
                'ref_model_name' => 'City',
                'foreign_key' => 'receiver_city_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'parcel',
                'model_name' => 'ShipmentRequest',
                'ref_model_name' => 'Parcel',
                'foreign_key' => 'waybill_number',
                'reference_key' => 'waybill_number',
                'join_type' => 'left'
            ]
        ];
    }
}