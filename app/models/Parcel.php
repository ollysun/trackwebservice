<?php
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Parcel extends \Phalcon\Mvc\Model
{
    const TYPE_NORMAL = 1;
    const TYPE_RETURN = 2;
    const TYPE_EXPRESS = 3;

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $parcel_type;

    /**
     *
     * @var integer
     */
    protected $sender_id;

    /**
     *
     * @var integer
     */
    protected $sender_address_id;

    /**
     *
     * @var integer
     */
    protected $receiver_id;

    /**
     *
     * @var integer
     */
    protected $receiver_address_id;

    /**
     *
     * @var integer
     */
    protected $from_branch_id;

    /**
     *
     * @var integer
     */
    protected $to_branch_id;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var float
     */
    protected $weight;

    /**
     *
     * @var double
     */
    protected $amount_due;

    /**
     *
     * @var integer
     */
    protected $cash_on_delivery;

    /**
     *
     * @var double
     */
    protected $cash_on_delivery_amount;

    /**
     *
     * @var integer
     */
    protected $delivery_type;

    /**
     *
     * @var double
     */
    protected  $package_value;

    /**
     *
     * @var integer
     */
    protected $no_of_package;

    /**
     *
     * @var string
     */
    protected $other_info;

    /**
     *
     * @var integer
     */
    protected $payment_type;

    /**
     *
     * @var integer
     */
    protected $shipping_type;

    /**
     *
     * @var double
     */
    protected $cash_amount;

    /**
     *
     * @var double
     */
    protected $pos_amount;

    /**
     *
     * @var string
     */
    protected $waybill_number;

    /**
     *
     * @var string
     */
    protected $created_date;

    /**
     *
     * @var string
     */
    protected $modified_date;

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
     * Method to set the value of field parcel_type
     *
     * @param integer $parcel_type
     * @return $this
     */
    public function setParcelType($parcel_type)
    {
        $this->parcel_type = $parcel_type;

        return $this;
    }

    /**
     * Method to set the value of field sender_id
     *
     * @param integer $sender_id
     * @return $this
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;

        return $this;
    }

    /**
     * Method to set the value of field sender_address_id
     *
     * @param integer $sender_address_id
     * @return $this
     */
    public function setSenderAddressId($sender_address_id)
    {
        $this->sender_address_id = $sender_address_id;

        return $this;
    }

    /**
     * Method to set the value of field receiver_id
     *
     * @param integer $receiver_id
     * @return $this
     */
    public function setReceiverId($receiver_id)
    {
        $this->receiver_id = $receiver_id;

        return $this;
    }

    /**
     * Method to set the value of field receiver_address_id
     *
     * @param integer $receiver_address_id
     * @return $this
     */
    public function setReceiverAddressId($receiver_address_id)
    {
        $this->receiver_address_id = $receiver_address_id;

        return $this;
    }

    /**
     * Method to set the value of field from_branch_id
     *
     * @param integer $from_branch_id
     * @return $this
     */
    public function setFromBranchId($from_branch_id)
    {
        $this->from_branch_id = $from_branch_id;

        return $this;
    }

    /**
     * Method to set the value of field to_branch_id
     *
     * @param integer $to_branch_id
     * @return $this
     */
    public function setToBranchId($to_branch_id)
    {
        $this->to_branch_id = $to_branch_id;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field weight
     *
     * @param float $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Method to set the value of field amount_due
     *
     * @param double $amount_due
     * @return $this
     */
    public function setAmountDue($amount_due)
    {
        $this->amount_due = $amount_due;

        return $this;
    }

    /**
     * Method to set the value of field cash_on_delivery
     *
     * @param integer $cash_on_delivery
     * @return $this
     */
    public function setCashOnDelivery($cash_on_delivery)
    {
        $this->cash_on_delivery = $cash_on_delivery;

        return $this;
    }

    /**
     * Method to set the value of field delivery_amount
     *
     * @param double $delivery_amount
     * @return $this
     */
    public function setCashOnDeliveryAmount($delivery_amount)
    {
        $this->cash_on_delivery_amount = $delivery_amount;

        return $this;
    }

    /**
     * Method to set the value of field delivery_type
     *
     * @param integer $delivery_type
     * @return $this
     */
    public function setDeliveryType($delivery_type)
    {
        $this->delivery_type = $delivery_type;

        return $this;
    }

    /**
     * Method to set the value of field package_value
     *
     * @param double $package_value
     * @return $this
     */
    public function setPackageValue($package_value)
    {
        $this->package_value = $package_value;

        return $this;
    }

    /**
     * Method to set the value of field no_of_package
     *
     * @param integer $no_of_package
     * @return $this
     */
    public function setNoOfPackage($no_of_package)
    {
        $this->no_of_package = $no_of_package;

        return $this;
    }

    /**
     * Method to set the value of field package_value
     *
     * @param double $other_info
     * @return $this
     */
    public function setOtherInfo($other_info)
    {
        $this->other_info = Text::removeExtraSpaces($other_info);

        return $this;
    }

    /**
     * Method to set the value of field payment_type
     *
     * @param integer $payment_type
     * @return $this
     */
    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    /**
     * Method to set the value of field shipping_type
     *
     * @param integer $shipping_type
     * @return $this
     */
    public function setShippingType($shipping_type)
    {
        $this->shipping_type = $shipping_type;

        return $this;
    }

    /**
     * Method to set the value of field cash_amount
     *
     * @param double $cash_amount
     * @return $this
     */
    public function setCashAmount($cash_amount)
    {
        $this->cash_amount = $cash_amount;

        return $this;
    }

    /**
     * Method to set the value of field pos_amount
     *
     * @param double $pos_amount
     * @return $this
     */
    public function setPosAmount($pos_amount)
    {
        $this->pos_amount = $pos_amount;

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
     * Method to set the value of field created_date
     *
     * @param string $created_date
     * @return $this
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }

    /**
     * Method to set the value of field modified_date
     *
     * @param string $modified_date
     * @return $this
     */
    public function setModifiedDate($modified_date)
    {
        $this->modified_date = $modified_date;

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
     * Returns the value of field parcel_type
     *
     * @return integer
     */
    public function getParcelType()
    {
        return $this->parcel_type;
    }

    /**
     * Returns the value of field sender_id
     *
     * @return integer
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Returns the value of field sender_address_id
     *
     * @return integer
     */
    public function getSenderAddressId()
    {
        return $this->sender_address_id;
    }

    /**
     * Returns the value of field receiver_id
     *
     * @return integer
     */
    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    /**
     * Returns the value of field receiver_address_id
     *
     * @return integer
     */
    public function getReceiverAddressId()
    {
        return $this->receiver_address_id;
    }

    /**
     * Returns the value of field from_branch_id
     *
     * @return integer
     */
    public function getFromBranchId()
    {
        return $this->from_branch_id;
    }

    /**
     * Returns the value of field to_branch_id
     *
     * @return int
     */
    public function getToBranchId()
    {
        return $this->to_branch_id;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Returns the value of field amount_due
     *
     * @return double
     */
    public function getAmountDue()
    {
        return $this->amount_due;
    }

    /**
     * Returns the value of field cash_on_delivery
     *
     * @return integer
     */
    public function getCashOnDelivery()
    {
        return $this->cash_on_delivery;
    }

    /**
     * Returns the value of field delivery_amount
     *
     * @return double
     */
    public function getCashOnDeliveryAmount()
    {
        return $this->cash_on_delivery_amount;
    }

    /**
     * Returns the value of field delivery_type
     *
     * @return integer
     */
    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    /**
     * Returns the value of field package_value
     *
     * @return double
     */
    public function getPackageValue()
    {
        return $this->package_value;
    }

    /**
     * Returns the value of field no_of_package
     *
     * @return integer
     */
    public function getNoOfPackage()
    {
        return $this->no_of_package;
    }

    /**
     * Returns the value of field other_info
     *
     * @return string
     */
    public function getOtherInfo()
    {
        return $this->other_info;
    }

    /**
     * Returns the value of field payment_type
     *
     * @return integer
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * Returns the value of field shipping_type
     *
     * @return integer
     */
    public function getShippingType()
    {
        return $this->shipping_type;
    }

    /**
     * Returns the value of field cash_amount
     *
     * @return double
     */
    public function getCashAmount()
    {
        return $this->cash_amount;
    }

    /**
     * Returns the value of field pos_amount
     *
     * @return double
     */
    public function getPosAmount()
    {
        return $this->pos_amount;
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
     * Returns the value of field created_date
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Returns the value of field modified_date
     *
     * @return string
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Parcel_history', 'parcel_id', array('alias' => 'Parcel_history'));
        $this->belongsTo('shipping_type', 'Shipping_type', 'id', array('alias' => 'Shipping_type'));
        $this->belongsTo('sender_id', 'User', 'id', array('alias' => 'Sender'));
        $this->belongsTo('sender_address_id', 'Address', 'id', array('alias' => 'SenderAddress'));
        $this->belongsTo('receiver_id', 'User', 'id', array('alias' => 'Receiver'));
        $this->belongsTo('receiver_address_id', 'Address', 'id', array('alias' => 'ReceiverAddress'));
        $this->belongsTo('from_branch_id', 'Branch', 'id', array('alias' => 'FromBranch'));
        $this->belongsTo('to_branch_id', 'Branch', 'id', array('alias' => 'ToBranch'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('delivery_type', 'Delivery_type', 'id', array('alias' => 'Delivery_type'));
        $this->belongsTo('payment_type', 'Payment_type', 'id', array('alias' => 'Payment_type'));
    }

    /**
     * @return Parcel[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Parcel
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'parcel_type' => 'parcel_type', 
            'sender_id' => 'sender_id', 
            'sender_address_id' => 'sender_address_id', 
            'receiver_id' => 'receiver_id', 
            'receiver_address_id' => 'receiver_address_id',
            'from_branch_id' => 'from_branch_id',
            'to_branch_id' => 'to_branch_id',
            'status' => 'status', 
            'weight' => 'weight', 
            'amount_due' => 'amount_due', 
            'cash_on_delivery' => 'cash_on_delivery', 
            'cash_on_delivery_amount' => 'cash_on_delivery_amount',
            'delivery_type' => 'delivery_type',
            'package_value' => 'package_value',
            'no_of_package' => 'no_of_package',
            'other_info' => 'other_info',
            'payment_type' => 'payment_type',
            'shipping_type' => 'shipping_type', 
            'cash_amount' => 'cash_amount', 
            'pos_amount' => 'pos_amount', 
            'waybill_number' => 'waybill_number', 
            'created_date' => 'created_date', 
            'modified_date' => 'modified_date'
        );
    }

    public function getData(){
        return array(
            'id' => $this->getId(),
            'parcel_type' => $this->getParcelType(),
            'sender_id' => $this->getSenderId(),
            'sender_address_id' => $this->getSenderAddressId(),
            'receiver_id' => $this->getReceiverId(),
            'receiver_address_id' => $this->getReceiverAddressId(),
            'from_branch_id' => $this->getFromBranchId(),
            'to_branch_id' => $this->getToBranchId(),
            'status' => $this->getStatus(),
            'weight' => $this->getWeight(),
            'amount_due' => $this->getAmountDue(),
            'cash_on_delivery' => $this->getCashOnDelivery(),
            'delivery_amount' => $this->getCashOnDeliveryAmount(),
            'delivery_type' => $this->getDeliveryType(),
            'package_value' => $this->getPackageValue(),
            'no_of_package' => $this->getNoOfPackage(),
            'other_info' => $this->getOtherInfo(),
            'payment_type' => $this->getPaymentType(),
            'shipping_type' => $this->getShippingType(),
            'cash_amount' => $this->getCashAmount(),
            'pos_amount' => $this->getPosAmount(),
            'waybill_number' => $this->getWaybillNumber(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate()
        );
    }

    public function initData($parcel_type, $sender_id, $sender_address_id, $receiver_id, $receiver_address_id,
        $weight, $amount_due, $cash_on_delivery, $delivery_amount, $delivery_type, $payment_type,
        $shipping_type, $from_branch_id, $to_branch_id, $status, $package_value, $no_of_package, $other_info, $cash_amount,
        $pos_amount
    ){
        $this->setParcelType($parcel_type);
        $this->setSenderId($sender_id);
        $this->setSenderAddressId($sender_address_id);
        $this->setReceiverId($receiver_id);
        $this->setReceiverAddressId($receiver_address_id);
        $this->setFromBranchId($from_branch_id);
        $this->setToBranchId($to_branch_id);
        $this->setWeight($weight);
        $this->setAmountDue($amount_due);
        $this->setCashOnDelivery($cash_on_delivery);
        $this->setCashOnDeliveryAmount($delivery_amount);
        $this->setDeliveryType($delivery_type);
        $this->setPaymentType($payment_type);
        $this->setShippingType($shipping_type);
        $this->setCashAmount($cash_amount);
        $this->setPosAmount($pos_amount);
        $this->setWaybillNumber(uniqid());
        $this->setOtherInfo($other_info);
        $this->setPackageValue($package_value);
        $this->setNoOfPackage($no_of_package);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus($status);
    }

    private function getParcelTypeLabel(){
        $parcel_type_label = "X";
        switch ($this->getParcelType()){
            case self::TYPE_NORMAL:
                $parcel_type_label = "N";
                break;
            case self::TYPE_EXPRESS:
                $parcel_type_label = "E";
                break;
            case self::TYPE_RETURN:
                $parcel_type_label = "R";
                break;
            default:
                break;
        }
        return $parcel_type_label;
    }

    public function generateWaybillNumber($initial_branch_id){
        $parcel_type_label = $this->getParcelTypeLabel();
        $waybill_number = $this->getDeliveryType()
            . $parcel_type_label
            . str_pad($initial_branch_id, 3, '0', STR_PAD_LEFT)
            . str_pad($this->getId(), 8, '0', STR_PAD_LEFT);

        $this->setWaybillNumber($waybill_number);
        $this->setModifiedDate('Y-m-d H:i:s');
    }

    public static function fetchOne($id){
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Parcel.*', 'Sender.*', 'Receiver.*', 'SenderAddress.*', 'ReceiverAddress.*'])
            ->from('Parcel')
            ->innerJoin('Sender', 'Sender.id = Parcel.sender_id', 'Sender')
            ->innerJoin('Receiver', 'Receiver.id = Parcel.receiver_id', 'Receiver')
            ->innerJoin('SenderAddress', 'SenderAddress.id = Parcel.sender_address_id', 'SenderAddress')
            ->innerJoin('ReceiverAddress', 'ReceiverAddress.id = Parcel.receiver_address_id', 'ReceiverAddress')
            ->where('Parcel.id = :id:');

        $data = $builder->getQuery()->execute(['id' => $id]);
        if (count($data) == 0) return false;

        $result = $data[0]->parcel->getData();
        $result['sender'] = $data[0]->sender->getData();
        $result['sender_address'] = $data[0]->senderAddress->getData();
        $result['receiver'] = $data[0]->receiver->getData();
        $result['receiver_address'] = $data[0]->receiverAddress->getData();

        return $result;
    }

    private static function filterConditions($filter_by){
        $bind = [];
        $where = [];

        //filters
        if (isset($filter_by['held_by_id'])){
            $where[] = 'HeldParcel.held_by_id = :held_by_id: AND HeldParcel.status = :held_status:';
            $bind['held_by_id'] = $filter_by['held_by_id'];
            $bind['held_status'] = Status::PARCEL_UNCLEARED;
        }else if (isset($filter_by['held_by_staff_id'])){
            $where[] = 'Admin.staff_id = :held_by_staff_id: AND HeldParcel.status = :held_status:';
            $bind['held_by_staff_id'] = $filter_by['held_by_staff_id'];
            $bind['held_status'] = Status::PARCEL_UNCLEARED;
        }

        if (isset($filter_by['to_branch_id'])){ $where[] = 'Parcel.to_branch_id = :to_branch_id:'; $bind['to_branch_id'] = $filter_by['to_branch_id'];}
        if (isset($filter_by['from_branch_id'])){ $where[] = 'Parcel.from_branch_id = :from_branch_id:'; $bind['from_branch_id'] = $filter_by['from_branch_id'];}
        if (isset($filter_by['parcel_type'])){ $where[] = 'Parcel.parcel_type = :parcel_type:'; $bind['parcel_type'] = $filter_by['parcel_type'];}
        if (isset($filter_by['sender_id'])){ $where[] = 'Parcel.sender_id = :sender_id:'; $bind['sender_id'] = $filter_by['sender_id'];}
        if (isset($filter_by['sender_address_id'])){ $where[] = 'Parcel.sender_address_id = :sender_address_id:'; $bind['sender_address_id'] = $filter_by['sender_address_id'];}
        if (isset($filter_by['receiver_id'])){ $where[] = 'Parcel.receiver_id = :receiver_id:'; $bind['receiver_id'] = $filter_by['receiver_id'];}
        if (isset($filter_by['receiver_address_id'])){ $where[] = 'Parcel.receiver_address_id = :receiver_address_id:'; $bind['receiver_address_id'] = $filter_by['receiver_address_id'];}
        if (isset($filter_by['status'])){ $where[] = 'Parcel.status = :status:'; $bind['status'] = $filter_by['status'];}
        if (isset($filter_by['min_weight'])){ $where[] = 'Parcel.weight >= :min_weight:'; $bind['min_weight'] = $filter_by['min_weight'];}
        if (isset($filter_by['max_weight'])){ $where[] = 'Parcel.weight <= :max_weight:'; $bind['max_weight'] = $filter_by['max_weight'];}
        if (isset($filter_by['min_amount_due'])){ $where[] = 'Parcel.amount_due >= :min_weight:'; $bind['min_amount_due'] = $filter_by['min_amount_due'];}
        if (isset($filter_by['max_amount_due'])){ $where[] = 'Parcel.amount_due <= :max_weight:'; $bind['max_amount_due'] = $filter_by['max_amount_due'];}
        if (isset($filter_by['cash_on_delivery'])){ $where[] = 'Parcel.cash_on_delivery = :cash_on_delivery:'; $bind['cash_on_delivery'] = $filter_by['cash_on_delivery'];}
        if (isset($filter_by['min_delivery_amount'])){ $where[] = 'Parcel.delivery_amount >= :min_delivery_amount:'; $bind['min_delivery_amount'] = $filter_by['min_delivery_amount'];}
        if (isset($filter_by['max_delivery_amount'])){ $where[] = 'Parcel.delivery_amount <= :max_delivery_amount:'; $bind['max_delivery_amount'] = $filter_by['max_delivery_amount'];}
        if (isset($filter_by['delivery_type'])){ $where[] = 'Parcel.delivery_type = :delivery_type:'; $bind['delivery_type'] = $filter_by['delivery_type'];}
        if (isset($filter_by['payment_type'])){ $where[] = 'Parcel.payment_type = :payment_type:'; $bind['payment_type'] = $filter_by['payment_type'];}
        if (isset($filter_by['shipping_type'])){ $where[] = 'Parcel.shipping_type = :shipping_type:'; $bind['shipping_type'] = $filter_by['shipping_type'];}
        if (isset($filter_by['min_cash_amount'])){ $where[] = 'Parcel.cash_amount >= :min_cash_amount:'; $bind['min_cash_amount'] = $filter_by['min_cash_amount'];}
        if (isset($filter_by['max_cash_amount'])){ $where[] = 'Parcel.cash_amount <= :max_cash_amount:'; $bind['max_cash_amount'] = $filter_by['max_cash_amount'];}
        if (isset($filter_by['min_pos_amount'])){ $where[] = 'Parcel.pos_amount >= :min_pos_amount:'; $bind['min_pos_amount'] = $filter_by['min_pos_amount'];}
        if (isset($filter_by['max_pos_amount'])){ $where[] = 'Parcel.pos_amount <= :max_pos_amount:'; $bind['max_pos_amount'] = $filter_by['max_pos_amount'];}
        if (isset($filter_by['start_created_date'])){ $where[] = 'Parcel.created_date >= :start_created_date:'; $bind['start_created_date'] = $filter_by['start_created_date'];}
        if (isset($filter_by['end_created_date'])){ $where[] = 'Parcel.created_date <= :end_created_date:'; $bind['end_created_date'] = $filter_by['end_created_date'];}
        if (isset($filter_by['start_modified_date'])){ $where[] = 'Parcel.modified_date >= :start_modified_date:'; $bind['start_modified_date'] = $filter_by['start_modified_date'];}
        if (isset($filter_by['end_modified_date'])){ $where[] = 'Parcel.modified_date <= :end_modified_date:'; $bind['end_modified_date'] = $filter_by['end_modified_date'];}
        if (isset($filter_by['waybill_number'])){ $where[] = 'Parcel.waybill_number LIKE :waybill_number:'; $bind['waybill_number'] = '%' . $filter_by['waybill_number'] . '%';}

        return ['where' => $where, 'bind' => $bind];
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with){
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Parcel')
            ->limit($count, $offset);

        $columns = ['Parcel.*'];

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        if (isset($filter_by['start_modified_date']) or isset($filter_by['end_modified_date'])){
            $builder->orderBy('modified_date');
        }

        if (isset($filter_by['held_by_id'])){
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id');
        }else if (isset($filter_by['held_by_staff_id'])){
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id');
            $builder->innerJoin('Admin', 'Admin.id = HeldParcel.held_by_id');
        }

        //model hydration
        if (isset($fetch_with['with_to_branch'])){
            $columns[] = 'ToBranch.*';
            $builder->innerJoin('ToBranch', 'ToBranch.id = Parcel.to_branch_id', 'ToBranch');
            $columns[] = 'ToBranchState.*';
            $builder->innerJoin('ToBranchState', 'ToBranchState.id = ToBranch.state_id', 'ToBranchState');
        }
        if (isset($fetch_with['with_from_branch'])){
            $columns[] = 'FromBranch.*';
            $builder->innerJoin('FromBranch', 'FromBranch.id = Parcel.from_branch_id', 'FromBranch');
            $columns[] = 'FromBranchState.*';
            $builder->innerJoin('FromBranchState', 'FromBranchState.id = FromBranch.state_id', 'FromBranchState');
        }
        if (isset($fetch_with['with_sender_address'])){
            $columns[] = 'SenderAddress.*';
            $builder->innerJoin('SenderAddress', 'SenderAddress.id = Parcel.sender_address_id', 'SenderAddress');
            $columns[] = 'SenderAddressState.*';
            $builder->innerJoin('SenderAddressState', 'SenderAddressState.id = SenderAddress.state_id', 'SenderAddressState');
        }
        if (isset($fetch_with['with_receiver_address'])){
            $columns[] = 'ReceiverAddress.*';
            $builder->innerJoin('ReceiverAddress', 'ReceiverAddress.id = Parcel.receiver_address_id', 'ReceiverAddress');
            $columns[] = 'ReceiverAddressState.*';
            $builder->innerJoin('ReceiverAddressState', 'ReceiverAddressState.id = ReceiverAddress.state_id', 'ReceiverAddressState');
        }
        if (isset($fetch_with['with_holder'])){
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id AND HeldParcel.status = ' . Status::PARCEL_UNCLEARED);
            $builder->innerJoin('Admin', 'Admin.id = HeldParcel.held_by_id');
            $columns[] = 'Admin.*';
        }

        if (isset($fetch_with['with_sender'])){ $columns[] = 'Sender.*'; $builder->innerJoin('Sender', 'Sender.id = Parcel.sender_id', 'Sender'); }
        if (isset($fetch_with['with_receiver'])){ $columns[] = 'Receiver.*'; $builder->innerJoin('Receiver', 'Receiver.id = Parcel.receiver_id', 'Receiver'); }

        $builder->where(join(' AND ', $where));
        $builder->columns($columns);
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach($data as $item){
            $parcel = [];
            if ($item->parcel == null){
                $parcel = $item->getData();
            }else{
                $parcel = $item->parcel->getData();
                if (isset($fetch_with['with_holder'])){
                    $parcel['holder'] = $item->admin->getData();
                }
                if (isset($fetch_with['with_to_branch'])) {
                    $parcel['to_branch'] = $item->toBranch->getData();
                    $parcel['to_branch']['state'] = $item->toBranchState->getData();
                }
                if (isset($fetch_with['with_from_branch'])) {
                    $parcel['from_branch'] = $item->fromBranch->getData();
                    $parcel['from_branch']['state'] = $item->fromBranchState->getData();
                }
                if (isset($fetch_with['with_sender'])) $parcel['sender'] = $item->sender->getData();
                if (isset($fetch_with['with_sender_address'])) {
                    $parcel['sender_address'] = $item->senderAddress->getData();
                    $parcel['sender_address']['state'] = $item->senderAddressState->getData();
                }
                if (isset($fetch_with['with_receiver'])) $parcel['receiver'] =$item->receiver->getData();
                if (isset($fetch_with['with_receiver_address'])) {
                    $parcel['receiver_address'] = $item->receiverAddress->getData();
                    $parcel['receiver_address']['state'] = $item->receiverAddressState->getData();
                }
            }
            $result[] = $parcel;
        }
        return $result;
    }

    public static function parcelCount($filter_by){
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS parcel_count')
            ->from('Parcel');

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0){
            return null;
        }

        return intval($data[0]->parcel_count);
    }

    /**
     * @param int $from_branch_id
     * @param int $to_branch_id
     * @param array $sender
     * @param array $sender_address
     * @param array $receiver
     * @param array $receiver_address
     * @param array $bank_account
     * @param array $parcel_data
     * @return bool
     */
    public function saveForm($from_branch_id, $sender, $sender_address, $receiver, $receiver_address, $bank_account, $parcel_data, $to_branch_id, $admin_id){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $check = true;
            $this->setTransaction($transaction);

            //saving the sender's user info
            $sender_obj = User::fetchByPhone($sender['phone']);
            $is_sender_existing = $sender_obj != false;
            if (!$is_sender_existing){
                $sender_obj = new User();
            }
            $sender_obj->setTransaction($transaction);
            $sender_obj->initData($sender['firstname'], $sender['lastname'], $sender['phone'], $sender['email'], null, $is_sender_existing);
            $check = $sender_obj->save();

            //saving the receiver's user info
            $receiver_obj = new User();
            $is_receiver_existing = false;
            if ($check) {
                $receiver_obj = User::fetchByPhone($receiver['phone']);
                $is_receiver_existing = $receiver_obj != false;
                if (!$is_receiver_existing){
                    $receiver_obj = new User();
                }
                $receiver_obj->setTransaction($transaction);
                $receiver_obj->initData($receiver['firstname'], $receiver['lastname'], $receiver['phone'], $receiver['email'], null, $is_receiver_existing);
                $check = $receiver_obj->save();
            }

            //saving the sender's address
            $sender_addr_obj = new Address();
            $is_existing = false;
            if ($check) {
                if ($sender_address['id'] != null) {
                    $sender_addr_obj = Address::fetchById($sender_address['id']);
                    $is_existing = ($sender_addr_obj != false);
                    if (!$is_existing){
                        $sender_addr_obj = new Address();
                    }
                }
                if ($is_existing and ($sender_addr_obj->getOwnerId() != $sender_obj->getId() OR $sender_addr_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)){
                    $transactionManager->rollback();
                    return false;
                }
                $sender_addr_obj->setTransaction($transaction);
                $sender_addr_obj->initData($sender_obj->getId(), OWNER_TYPE_CUSTOMER,
                    $sender_address['street1'], $sender_address['street2'], intval($sender_address['state_id']),
                    intval($sender_address['country_id']), $sender_address['city'], $is_existing, $is_sender_existing);

                $check = $sender_addr_obj->save();
            }

            //saving the receiver's address
            $receiver_addr_obj = new Address();
            $is_existing = false;
            if ($check) {
                if ($receiver_address['id'] != null) {
                    $receiver_addr_obj = Address::fetchById($receiver_address['id']);
                    $is_existing = ($receiver_addr_obj != false);
                    if (!$is_existing){
                        $receiver_addr_obj = new Address();
                    }
                }
                if ($is_existing and ($receiver_addr_obj->getOwnerId() != $receiver_obj->getId() OR $receiver_addr_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)){
                    $transactionManager->rollback();
                    return false;
                }
                $receiver_addr_obj->setTransaction($transaction);
                $receiver_addr_obj->initData($receiver_obj->getId(), OWNER_TYPE_CUSTOMER,
                    $receiver_address['street1'], $receiver_address['street2'], $receiver_address['state_id'],
                    $receiver_address['country_id'], $receiver_address['city'], $is_existing, $is_receiver_existing);
                $check = $receiver_addr_obj->save();
            }

            //saving a bank account if any was provided
            if ($bank_account != null) {
                $bank_account_obj = new BankAccount();
                $is_existing = false;
                if ($check) {
                    if ($bank_account['id'] != null) {
                        $bank_account_obj = BankAccount::fetchById($bank_account['id']);
                        $is_existing = ($bank_account_obj != false);
                        if ($is_existing){
                            $bank_account_obj = new BankAccount();
                        }
                    }
                    if ($is_existing and ($bank_account_obj->getOwnerId() != $sender_obj->getId() OR $bank_account_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)){
                        $transactionManager->rollback();
                        return false;
                    }
                    $bank_account_obj->setTransaction($transaction);
                    $bank_account_obj->initData($sender_obj->getId(), OWNER_TYPE_CUSTOMER, $bank_account['bank_id'],
                        $bank_account['account_name'], $bank_account['account_no'], $bank_account['sort_code'], $is_existing);
                    $check = $bank_account_obj->save();
                }
            }


            //finally saving the parcel

            $parcel_status = ($to_branch_id == $from_branch_id) ? Status::PARCEL_FOR_DELIVERY : Status::PARCEL_FOR_SWEEPER;
            if ($check){
                $this->initData($parcel_data['parcel_type'], $sender_obj->getId(), $sender_addr_obj->getId(),
                    $receiver_obj->getId(), $receiver_addr_obj->getId(), $parcel_data['weight'], $parcel_data['amount_due'],
                    $parcel_data['cash_on_delivery'], $parcel_data['cash_on_delivery_amount'], $parcel_data['delivery_type'],
                    $parcel_data['payment_type'], $parcel_data['shipping_type'], $from_branch_id, $to_branch_id, $parcel_status,
                    $parcel_data['package_value'], $parcel_data['no_of_package'], $parcel_data['other_info'], $parcel_data['cash_amount'],
                    $parcel_data['pos_amount']
                    );
                $check = $this->save();
            }

            //saving the parcel
            if ($check){
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $history_desc = ($to_branch_id == $from_branch_id) ? ParcelHistory::MSG_FOR_DELIVERY : ParcelHistory::MSG_FOR_SWEEPER;
                $parcel_history->initData($this->getId(), $from_branch_id, $history_desc, $admin_id, $parcel_status);
                $check = $parcel_history->save();
            }

            //setting waybill number
            if ($check){
                $this->generateWaybillNumber($from_branch_id);
                $check = $this->save();
            }

            if ($check){
                $transactionManager->commit();
                return true;
            }
        } catch (Exception $e) {

        }

        $transactionManager->rollback();
        return false;
    }

    public function changeStatus($status, $admin_id, $history_desc){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setStatus($status);
            $this->setModifiedDate(date('Y-m-d H:i:s'));

            if ($this->save()){
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $parcel_history->initData($this->getId(), $this->getToBranchId(), $history_desc, $admin_id, $status);
                if ($parcel_history->save()){
                    $transactionManager->commit();
                    return true;
                }
            }
        }catch (Exception $e) {

        }

        $transactionManager->rollback();
        return false;
    }

    public function changeDestination($status, $to_branch_id, $admin_id, $history_desc){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $from_branch_id = $this->getFromBranchId();
            $this->setStatus($status);
            $this->setFromBranchId($this->getToBranchId());
            $this->setToBranchId($to_branch_id);
            $this->setModifiedDate(date('Y-m-d H:i:s'));

            if ($this->save()){
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $parcel_history->initData($this->getId(), $from_branch_id, $history_desc, $admin_id, $status);
                if ($parcel_history->save()){
                    $transactionManager->commit();
                    return true;
                }
            }
        }catch (Exception $e) {

        }

        $transactionManager->rollback();
        return false;
    }

    public function checkout($status, $held_by_id, $admin_id, $history_desc){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->setStatus($status);
            $this->setModifiedDate(date('Y-m-d H:i:s'));

            if ($this->save()){
                $held_parcel = new HeldParcel();
                $held_parcel->setTransaction($transaction);
                $held_parcel->initData($this->getId(), $held_by_id);
                if($held_parcel->save()){
                    $parcel_history = new ParcelHistory();
                    $parcel_history->setTransaction($transaction);
                    $parcel_history->initData($this->getId(), $this->getFromBranchId(), $history_desc, $admin_id, $status);
                    if ($parcel_history->save()){
                        $transactionManager->commit();
                        return true;
                    }
                }
            }
        }catch (Exception $e) {

        }

        $transactionManager->rollback();
//        exit();
        return false;
    }

    /**
     * @param HeldParcel $held_parcel_record
     * @param int $admin_id
     * @return bool
     */
    public function checkIn($held_parcel_record, $admin_id)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->setStatus(Status::PARCEL_ARRIVAL);
            $this->setModifiedDate(date('Y-m-d H:i:s'));

            if ($this->save()) {
                if ($held_parcel_record != false) {
                    $held_parcel_record->setTransaction($transaction);
                    $held_parcel_record->clear();
                    if ($held_parcel_record->save()) {
                        $parcel_history = new ParcelHistory();
                        $parcel_history->setTransaction($transaction);
                        $parcel_history->initData($this->getId(), $this->getToBranchId(), ParcelHistory::MSG_FOR_ARRIVAL, $admin_id, Status::PARCEL_ARRIVAL);
                        if ($parcel_history->save()){
                            $transactionManager->commit();
                            return true;
                        }
                    }
                }
            }
        } catch (Exception $e) {

        }

        $transactionManager->rollback();
        return false;
    }

    public static function getByWaybillNumber($waybill_number){
        return Parcel::findFirst([
            'waybill_number = :waybill_number:',
            'bind' => ['waybill_number' => trim(strtoupper($waybill_number))]
        ]);
    }
}
