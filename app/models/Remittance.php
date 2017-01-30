<?php

/**
 * Remitance
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-01-23, 10:45:02
 */


class Remittance extends \Phalcon\Mvc\Model
{
    const SQL_UN_PAID = "select * from parcel left join remitance on 
	  parcel.waybill_number = remitance.waybill_number where remitance.id is null";

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $waybill_number;

    /**
     *
     * @var string
     */
    protected $company_registration_number;

    /**
     *
     * @var string
     */
    protected $payer_id;

    /**
     *
     * @var string
     */
    protected $amount;

    /**
     *
     * @var string
     */
    protected $date;

    /**
     *
     * @var string
     */
    protected $ref;

    /**
     *
     * @var string
     */
    protected $status;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field waybill_number
     *
     * @param string $waybill_number
     * @return $this
     */
    public function setWaybillNumber($waybill_number)
    {
        $this->waybill_number = $waybill_number;

        return $this;
    }

    /**
     * Method to set the value of field company_registration_number
     *
     * @param string $company_registration_number
     * @return $this
     */
    public function setCompanyRegistrationNumber($company_registration_number)
    {
        $this->company_registration_number = $company_registration_number;

        return $this;
    }

    /**
     * Method to set the value of field payer_id
     *
     * @param string $payer_id
     * @return $this
     */
    public function setPayerId($payer_id)
    {
        $this->payer_id = $payer_id;

        return $this;
    }

    /**
     * Method to set the value of field amount
     *
     * @param string $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Method to set the value of field date
     *
     * @param string $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Method to set the value of field date
     *
     * @param string $ref
     * @return $this
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }
    /**
     * Method to set the value of field date
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field waybill_number
     *
     * @return string
     */
    public function getWaybillNumber()
    {
        return $this->waybill_number;
    }

    /**
     * Returns the value of field company_registration_number
     *
     * @return string
     */
    public function getCompanyRegistrationNumber()
    {
        return $this->company_registration_number;
    }

    /**
     * Returns the value of field payer_id
     *
     * @return string
     */
    public function getPayerId()
    {
        return $this->payer_id;
    }

    /**
     * Returns the value of field amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the value of field date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Returns the value of field ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Returns the value of field status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('payer_id', 'Admin', 'staff_id', array('alias' => 'Admin'));
        $this->belongsTo('company_registration_number', 'Company', 'reg_no', array('alias' => 'Company'));
        $this->belongsTo('waybill_number', 'Parcel', 'waybill_number', array('alias' => 'Parcel'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'remittance';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Remittance[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Remittance
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'waybill_number' => 'waybill_number',
            'company_registration_number' => 'company_registration_number',
            'payer_id' => 'payer_id',
            'amount' => 'amount',
            'date' => 'date',
            'ref' => 'ref',
            'status' => 'status'
        );
    }

    public function init($waybill_number, $amount, $company_regitration_number, $payer_id, $ref, $status){
        $this->setWaybillNumber($waybill_number);
        $this->setAmount($amount);
        $this->setCompanyRegistrationNumber($company_regitration_number);
        $this->setPayerId($payer_id);
        $this->setDate(date('Y-m-d H:i:s'));
        $this->setRef($ref);
        $this->setStatus($status);
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'waybill_number' => $this->getWaybillNumber(),
            'amount' => $this->getAmount(),
            'company_registration_number' => $this->getCompanyRegistrationNumber(),
            'payer_id' => $this->getPayerId(),
            'date' => $this->getDate(),
            'ref' => $this->getRef(),
            'status' => $this->getStatus()
        );
    }

    /**
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @param null $order_by_clause
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with, $order_by_clause = null)
    {
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Remittance');

        if (!isset($filter_by['send_all'])) {
            $builder->limit($count, $offset);
        }

        $columns = ['Remittance.*'];

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        if ($order_by_clause != null) {
            $builder->orderBy($order_by_clause);
        } else {
            $builder->orderBy('Remittance.id');
        }

        //model hydration
        if (isset($fetch_with['with_parcel'])) {
            $columns[] = 'Parcel.*';
            $builder->innerJoin('Parcel', 'Parcel.waybill_number = Remittance.waybill_number');
        }
        if (isset($fetch_with['with_company'])) {
            $columns[] = 'Company.*';
            $builder->innerJoin('Company', 'Company.reg_no = Remittance.company_registration_number');
        }
        if (isset($fetch_with['with_payer'])) {
            $columns[] = 'Admin.*';
            $builder->leftJoin('Admin', 'Admin.id = Remittance.paid_by');
        }

        $builder->where(join(' AND ', $where));

        if (isset($filter_by['waybill_numbers'])) {
            $waybill_numbers = explode(',', $filter_by['waybill_numbers']);

            $builder->inWhere('Remittance.waybill_number', $waybill_numbers);
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            $remittance = [];
            if ($item->remitance == null) {
                $remittance = $item->getData();
            } else {
                $remittance = $item->remittance->getData();
                if (isset($fetch_with['with_parcel'])) {
                    $remittance['parcel'] = $item->parcel->getData();
                }
                if (isset($fetch_with['with_company'])) {
                    $remittance['company'] = $item->company->getData();
                }
                if (isset($fetch_with['with_payer'])) {
                    $remittance['payer'] = $item->admin->getData();
                }
            }
            $result[] = $remittance;
        }
        return $result;
    }

    public static function fetchDueForPaymentParcels($offset, $count, $filter_by){
        //fetch delivered parcels that are cash on delivery but have no record in remittance table
        $builder = (new Parcel())->getModelsManager()->createBuilder()->from('Parcel');
        $builder->leftJoin('Remittance', 'Parcel.waybill_number = Remittance.waybill_number', 'Remittance');
        $builder->innerJoin('Company', 'Company.id = Parcel.company_id', 'Company');
        $builder->innerJoin('CodTellerParcel', 'CodTellerParcel.parcel_id = Parcel.id', 'CodTellerParcel');


        if (!isset($filter_by['send_all'])) {
            $builder->limit($count, $offset);
        }
        $columns = ['Parcel.*', 'Company.*'];
        $where = ['Remittance.id IS NULL', 'Parcel.status = :delivered_status:',
                'Parcel.cash_on_delivery = :cod:', 'CodTellerParcel.id IS NOT NULL'];
        $bind = ['delivered_status' => Status::PARCEL_DELIVERED, 'cod' => 1];

        if(isset($filter_by['company_id'])){
            $where[] = 'Parcel.company_id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        $builder->where(join(' AND ', $where));

        $builder->columns($columns);

        /** @var Phalcon\Mvc\Model\Resultset\Complex $data */
        $data = $builder->getQuery()->execute($bind);


        $result = [];
        foreach ($data as $item) {
            if ($item->parcel == null) {
                $parcel = $item->getData();
            } else {
                $parcel = $item->parcel->getData();
                $parcel['company'] = $item->company->getData();
            }
            $result[] = $parcel;
        }
        return $result;
    }

    public static function dueForPaymentParcelCount($filter_by){
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS total_count')
            ->from('Parcel');
        $builder->innerJoin('Remittance', 'Parcel.waybill_number = Remittance.waybill_number', 'Remittance');
        $builder->innerJoin('CodTellerParcel', 'CodTellerParcel.parcel_id = Parcel.id', 'CodTellerParcel');

        $where = ['Remittance.id is null', 'Parcel.status = :delivered_status:', 'Parcel.cash_on_delivery = :cod:'];
        $bind = ['delivered_status' => Status::PARCEL_DELIVERED, 'cod' => 1];

        if(isset($filter_by['company_id'])){
            $where[] = 'Parcel.company_id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->total_count);
    }

    public static function fetchOne($id)
    {
        $obj = new RtdTeller();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Remittance.*', 'Parcel.*, Company.*, Admin.*'])
            ->from('RtdTeller')
            ->innerJoin('Company', 'Company.reg_no = Remittance.company_registration_number', 'Company')
            ->innerJoin('Parcel', 'Parcel.id = Remittance.parcel_id', 'Parcel')
            ->innerJoin('Admin', 'Admin.staff_id = Remittance.payer_id', 'Admin')
            ->where('Remittance.id = :id:');

        $data = $builder->getQuery()->execute(['id' => $id]);
        if (count($data) == 0) return false;

        $result = $data[0]->remittance->getData();
        $result['parcel'] = $data[0]->parcel->getData();
        $result['payer'] = $data[0]->admin->getData();
        $result['company'] = $data[0]->company->getData();

        return $result;
    }

    private static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];

        //filters
        if (isset($filter_by['waybill_number'])) {
            $where[] = 'Remittance.waybill_number = :waybill_number:';
            $bind['waybill_number'] = $filter_by['waybill_number'];
        }
        if (isset($filter_by['company_registration_number'])) {
            $where[] = 'Remittance.company_registration_number = :company_registration_number:';
            $bind['company_registration_number'] = $filter_by['company_registration_number'];
        }
        if (isset($filter_by['min_amount'])) {
            $where[] = 'Remittance.amount >= :min_amount:';
            $bind['min_amount'] = $filter_by['min_amount'];
        }
        if (isset($filter_by['max_amount'])) {
            $where[] = 'Remittance.amount <= :max_amount:';
            $bind['max_amount'] = $filter_by['max_amount'];
        }
        if (isset($filter_by['start_date'])) {
            $where[] = 'Remittance.date >= :start_date:';
            $bind['start_date'] = $filter_by['start_date'];
        }
        if (isset($filter_by['end_date'])) {
            $where[] = 'Remittance.date <= :end_date:';
            $bind['end_date'] = $filter_by['end_date'];
        }

        if (isset($filter_by['status'])) {
            $where[] = 'Remittance.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        return ['where' => $where, 'bind' => $bind];
    }

    /**
     * Returns the number of teller based on condition
     *
     * @param array
     * @return int
     * @author  Olawale Lawal
     */
    public static function remittanceCount($filter_by)
    {
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS total_count')
            ->from('Remittance');

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->total_count);
    }

    /**
     * @param $offset
     * @param $count
     * @param $filter_by
     * @return mixed
     */
    public static function fetchPendingDuePaymentAdvice($offset, $count, $filter_by){
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from(array('c' => 'Company'));
        $builder->innerJoin('Parcel', 'c.id = p.company_id', 'p');
        $builder->leftJoin('Remittance', 'p.waybill_number = Remittance.waybill_number', 'Remittance');
        $builder->innerJoin('CodTellerParcel', 'CodTellerParcel.parcel_id = p.id', 'CodTellerParcel');

        if(!$filter_by['send_all']){
            $builder->limit($count, $offset);
        }
        $where = ['Remittance.id IS NULL', 'p.status = :delivered_status:',
            'p.cash_on_delivery = :cod:', 'CodTellerParcel.id IS NOT NULL'];
        $bind = ['delivered_status' => Status::PARCEL_DELIVERED, 'cod' => 1];

        if(isset($filter_by['company_id'])){
            $where[] = 'c.id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder
            ->columns(array(
                'c.*',
                'SUM(p.cash_on_delivery_amount) AS amount'
            ))
            ->groupBy('c.id')->getQuery()->execute($bind);
        ;

        $results = [];
        foreach ($data as $item) {
            $result = $item->c->getData();
            $result['amount'] = $item->amount;
            $results[] = $result;
        }

        return $results;
    }

    /**
     * @param $filter_by
     * @return int|null
     */
    public static function countPendingDuePaymentAdvice($filter_by){
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from(array('c' => 'Company'));
        $builder->innerJoin('Parcel', 'c.id = p.company_id', 'p');
        $builder->leftJoin('Remittance', 'p.waybill_number = Remittance.waybill_number', 'Remittance');
        $builder->innerJoin('CodTellerParcel', 'CodTellerParcel.parcel_id = p.id', 'CodTellerParcel');

        $where = ['Remittance.id IS NULL', 'p.status = :delivered_status:',
            'p.cash_on_delivery = :cod:', 'CodTellerParcel.id IS NOT NULL', 'amount > 0'];
        $bind = ['delivered_status' => Status::PARCEL_DELIVERED, 'cod' => 1];

        if(isset($filter_by['company_id'])){
            $where[] = 'c.id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder
            ->columns(array(
                'COUNT(c.id) AS total_count'
            ))
            ->groupBy('c.id')->getQuery()->execute($bind);
        ;

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->total_count);
    }

    /**
     * @param $offset
     * @param $count
     * @param $filter_by
     * @return mixed
     */
    public static function fetchDuePaymentAdvice($offset, $count, $ref, $filter_by){
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from(array('c' => 'Company'));
        $builder->innerJoin('Remittance', 'c.reg_no = r.company_registration_number', 'r');

        if(!$filter_by['send_all']){
            $builder->limit($count, $offset);
        }
        $where = ['r.ref = :ref:'];
        $bind = ['ref' => $ref];

        if(isset($filter_by['company_id'])){
            $where[] = 'c.id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder
            ->columns(array(
                'c.*',
                'SUM(r.amount) AS amount'
            ))
            ->groupBy('c.id')->getQuery()->execute($bind);
        ;

        $results = [];
        foreach ($data as $item) {
            $result = $item->c->getData();
            $result['amount'] = $item->amount;
            $results[] = $result;
        }

        return $results;
    }

    /**
     * @param $filter_by
     * @return int|null
     */
    public static function countDuePaymentAdvice($ref, $filter_by){
        $obj = new Remittance();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from(array('c' => 'Company'));
        $builder->leftJoin('Remittance', 'p.waybill_number = r.waybill_number', 'r');

        $where = ['r.ref = :ref:'];
        $bind = ['ref' => $ref];

        if(isset($filter_by['company_id'])){
            $where[] = 'c.id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        $builder->where(join(' AND ', $where));

        $data = $builder
            ->columns(array(
                'COUNT(c.id) AS total_count'
            ))
            ->groupBy('c.id')->getQuery()->execute($bind);
        ;

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->total_count);
    }

}