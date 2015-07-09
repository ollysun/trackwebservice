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
    protected $delivery_amount;

    /**
     *
     * @var integer
     */
    protected $delivery_type;

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
    public function setDeliveryAmount($delivery_amount)
    {
        $this->delivery_amount = $delivery_amount;

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
    public function getDeliveryAmount()
    {
        return $this->delivery_amount;
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
            'status' => 'status', 
            'weight' => 'weight', 
            'amount_due' => 'amount_due', 
            'cash_on_delivery' => 'cash_on_delivery', 
            'delivery_amount' => 'delivery_amount', 
            'delivery_type' => 'delivery_type', 
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
            'status' => $this->getStatus(),
            'weight' => $this->getWeight(),
            'amount_due' => $this->getAmountDue(),
            'cash_on_delivery' => $this->getCashOnDelivery(),
            'delivery_amount' => $this->getDeliveryAmount(),
            'delivery_type' => $this->getDeliveryType(),
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
        $shipping_type
    ){
        $this->setParcelType($parcel_type);
        $this->setSenderId($sender_id);
        $this->setSenderAddressId($sender_address_id);
        $this->setReceiverId($receiver_id);
        $this->setReceiverAddressId($receiver_address_id);
        $this->setWeight($weight);
        $this->setAmountDue($amount_due);
        $this->setCashOnDelivery($cash_on_delivery);
        $this->setDeliveryAmount($delivery_amount);
        $this->setDeliveryType($delivery_type);
        $this->setPaymentType($payment_type);
        $this->setShippingType($shipping_type);
        $this->setCashAmount(0);
        $this->setPosAmount(0);
        $this->setWaybillNumber(uniqid());

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::COLLECTED);
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

//        var_dump($builder->getQuery());exit();
        $data = $builder->getQuery()->execute(['id' => $id]);
        if (count($data) == 0) return false;

        $result = $data[0]->parcel->getData();
        $result['sender'] = $data[0]->sender->getData();
        $result['sender_address'] = $data[0]->senderAddress->getData();
        $result['receiver'] = $data[0]->receiver->getData();
        $result['receiver_address'] = $data[0]->receiverAddress->getData();

        return $result;
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with){
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Parcel')
            ->limit($count, $offset);

        $bind = [];
        $columns = ['Parcel.*'];
        $where = [];

        //filters
        if (isset($filter_by['parcel_type'])){ $where[] = 'parcel_type = :parcel_type:'; $bind['parcel_type'] = $filter_by['parcel_type'];}
        if (isset($filter_by['sender_id'])){ $where[] = 'sender_id = :sender_id:'; $bind['sender_id'] = $filter_by['sender_id'];}
        if (isset($filter_by['sender_address_id'])){ $where[] = 'sender_address_id = :sender_address_id:'; $bind['sender_address_id'] = $filter_by['sender_address_id'];}
        if (isset($filter_by['receiver_id'])){ $where[] = 'receiver_id = :receiver_id:'; $bind['receiver_id'] = $filter_by['receiver_id'];}
        if (isset($filter_by['receiver_address_id'])){ $where[] = 'receiver_address_id = :receiver_address_id:'; $bind['receiver_address_id'] = $filter_by['receiver_address_id'];}
        if (isset($filter_by['status'])){ $where[] = 'status = :status:'; $bind['status'] = $filter_by['status'];}
        if (isset($filter_by['min_weight'])){ $where[] = 'weight >= :min_weight:'; $bind['min_weight'] = $filter_by['min_weight'];}
        if (isset($filter_by['max_weight'])){ $where[] = 'weight <= :max_weight:'; $bind['max_weight'] = $filter_by['max_weight'];}
        if (isset($filter_by['min_amount_due'])){ $where[] = 'amount_due >= :min_weight:'; $bind['min_amount_due'] = $filter_by['min_amount_due'];}
        if (isset($filter_by['max_amount_due'])){ $where[] = 'amount_due <= :max_weight:'; $bind['max_amount_due'] = $filter_by['max_amount_due'];}
        if (isset($filter_by['cash_on_delivery'])){ $where[] = 'cash_on_delivery = :cash_on_delivery:'; $bind['cash_on_delivery'] = $filter_by['cash_on_delivery'];}
        if (isset($filter_by['min_delivery_amount'])){ $where[] = 'delivery_amount >= :min_delivery_amount:'; $bind['min_delivery_amount'] = $filter_by['min_delivery_amount'];}
        if (isset($filter_by['max_delivery_amount'])){ $where[] = 'delivery_amount <= :max_delivery_amount:'; $bind['max_delivery_amount'] = $filter_by['max_delivery_amount'];}
        if (isset($filter_by['delivery_type'])){ $where[] = 'delivery_type = :delivery_type:'; $bind['delivery_type'] = $filter_by['delivery_type'];}
        if (isset($filter_by['payment_type'])){ $where[] = 'payment_type = :payment_type:'; $bind['payment_type'] = $filter_by['payment_type'];}
        if (isset($filter_by['shipping_type'])){ $where[] = 'shipping_type = :shipping_type:'; $bind['shipping_type'] = $filter_by['shipping_type'];}
        if (isset($filter_by['min_cash_amount'])){ $where[] = 'cash_amount >= :min_cash_amount:'; $bind['min_cash_amount'] = $filter_by['min_cash_amount'];}
        if (isset($filter_by['max_cash_amount'])){ $where[] = 'cash_amount <= :max_cash_amount:'; $bind['max_cash_amount'] = $filter_by['max_cash_amount'];}
        if (isset($filter_by['min_pos_amount'])){ $where[] = 'pos_amount >= :min_pos_amount:'; $bind['min_pos_amount'] = $filter_by['min_pos_amount'];}
        if (isset($filter_by['max_pos_amount'])){ $where[] = 'pos_amount <= :max_pos_amount:'; $bind['max_pos_amount'] = $filter_by['max_pos_amount'];}
        if (isset($filter_by['start_created_date'])){ $where[] = 'created_date >= :start_created_date:'; $bind['start_created_date'] = $filter_by['start_created_date'];}
        if (isset($filter_by['end_created_date'])){ $where[] = 'created_date <= :end_created_date:'; $bind['end_created_date'] = $filter_by['end_created_date'];}
        if (isset($filter_by['start_modified_date'])){ $where[] = 'modified_date >= :start_modified_date:'; $bind['start_modified_date'] = $filter_by['start_modified_date']; $builder->orderBy('modified_date');}
        if (isset($filter_by['end_modified_date'])){ $where[] = 'modified_date <= :end_modified_date:'; $bind['end_modified_date'] = $filter_by['end_modified_date']; $builder->orderBy('modified_date');}
        if (isset($filter_by['waybill_number'])){ $where[] = 'waybill_number LIKE :waybill_number:'; $bind['waybill_number'] = '%' . $filter_by['waybill_number'] . '%';}

        //model hydration
        if (isset($fetch_with['with_sender'])){ $columns[] = 'Sender.*'; $builder->innerJoin('Sender', 'Sender.id = Parcel.sender_id', 'Sender'); }
        if (isset($fetch_with['with_receiver'])){ $columns[] = 'Receiver.*'; $builder->innerJoin('Receiver', 'Receiver.id = Parcel.receiver_id', 'Receiver'); }
        if (isset($fetch_with['with_sender_address'])){ $columns[] = 'SenderAddress.*'; $builder->innerJoin('SenderAddress', 'SenderAddress.id = Parcel.sender_address_id', 'SenderAddress'); }
        if (isset($fetch_with['with_receiver_address'])){ $columns[] = 'ReceiverAddress.*'; $builder->innerJoin('ReceiverAddress', 'ReceiverAddress.id = Parcel.receiver_address_id', 'ReceiverAddress'); }

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
                if (isset($fetch_with['sender'])) $result['sender'] = $data[0]->sender->getData();
                if (isset($fetch_with['sender_address'])) $result['sender_address'] = $data[0]->senderAddress->getData();
                if (isset($fetch_with['receiver'])) $result['receiver'] = $data[0]->receiver->getData();
                if (isset($fetch_with['receiver_address'])) $result['receiver_address'] = $data[0]->receiverAddress->getData();
            }
            $result[] = $parcel;
        }
        return $result;
    }

    /**
     * @param int $branch_id
     * @param array $sender
     * @param array $sender_address
     * @param array $receiver
     * @param array $receiver_address
     * @param array $bank_account
     * @param array $parcel_data
     * @return bool
     */
    public function saveForm($branch_id, $sender, $sender_address, $receiver, $receiver_address, $bank_account, $parcel_data){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $check = true;
            $this->setTransaction($transaction);

            //saving the sender's user info
            $sender_obj = new User();
            $is_existing = false;
            $sender_obj = User::fetchByPhone($sender['phone']);
            $is_existing = ($sender_obj != false);
            $sender_obj->setTransaction($transaction);
            $sender_obj->initData($sender['firstname'], $sender['lastname'], $sender['phone'], $sender['email'], null, $is_existing);
            $check = $sender_obj->save();

            //saving the receiver's user info
            $receiver_obj = new User();
            $is_existing = false;
            if ($check) {
                $receiver_obj = User::fetchByPhone($receiver['phone']);
                $is_existing = ($receiver_obj != false);
                $receiver_obj->setTransaction($transaction);
                $receiver_obj->initData($receiver['firstname'], $receiver['lastname'], $receiver['phone'], $receiver['email'], null, $is_existing);
                $check = $receiver_obj->save();
            }

            //saving the sender's address
            $sender_addr_obj = new Address();
            $is_existing = false;
            if ($check) {
                if ($sender_address['id'] != null) {
                    $sender_addr_obj = Address::fetchById($sender_address['id']);
                    $is_existing = ($sender_addr_obj != false);
                }
                if ($is_existing and ($sender_addr_obj->getOwnerId() != $sender_obj->getId() OR $sender_addr_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)){
                    $transactionManager->rollback();
                    return false;
                }
                $sender_addr_obj->setTransaction($transaction);
                $sender_addr_obj->initData($sender_obj->getId(), OWNER_TYPE_CUSTOMER,
                    $sender_address['street_address1'], $sender_address['street_address2'], $sender_address['state_id'],
                    $sender_address['country_id'], $sender_address['city'], $is_existing);
                $check = $sender_addr_obj->save();
            }

            //saving the receiver's address
            $receiver_addr_obj = new Address();
            $is_existing = false;
            if ($check) {
                if ($receiver_address['id'] != null) {
                    $receiver_addr_obj = Address::fetchById($receiver_address['id']);
                    $is_existing = ($receiver_addr_obj != false);
                }
                if ($is_existing and ($receiver_addr_obj->getOwnerId() != $receiver_obj->getId() OR $receiver_addr_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)){
                    $transactionManager->rollback();
                    return false;
                }
                $receiver_addr_obj->setTransaction($transaction);
                $receiver_addr_obj->initData($receiver_obj->getId(), OWNER_TYPE_CUSTOMER,
                    $receiver_address['street_address1'], $receiver_address['street_address2'], $receiver_address['state_id'],
                    $receiver_address['country_id'], $receiver_address['city'], $is_existing);
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
            if ($check){
                $this->initData($parcel_data['parcel_type'], $sender_obj->getId(), $sender_addr_obj->getId(),
                    $receiver_obj->getId(), $receiver_addr_obj->getId(), $parcel_data['weight'], $parcel_data['amount_due'],
                    $parcel_data['cash_on_delivery'], $parcel_data['delivery_amount'], $parcel_data['delivery_type'],
                    $parcel_data['payment_type'], $parcel_data['shipping_type']
                    );
                $check = $this->save();
            }

            //saving the parcel
            if ($check){
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $parcel_history->initData($this->getId(), $branch_id, 'Parcel received');
                $check = $parcel_history->save();
            }

            //setting waybill number
            if ($check){
                $this->generateWaybillNumber($branch_id);
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
}
