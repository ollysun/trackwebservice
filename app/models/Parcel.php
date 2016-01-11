<?php
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Parcel extends \Phalcon\Mvc\Model
{

    const NOT_APPLICABLE = 'N/A';

    const TYPE_NORMAL = 1;
    const TYPE_RETURN = 2;
    const TYPE_EXPRESS = 3;

    const ENTITY_TYPE_NORMAL = 1;
    const ENTITY_TYPE_BAG = 2;
    const ENTITY_TYPE_SUB = 3;
    const ENTITY_TYPE_PARENT = 4;

    const SQL_MAKE_SUB_VISIBLE = 'UPDATE parcel SET is_visible = 1, modified_date = :modified_date WHERE id IN (SELECT child_id FROM linked_parcel WHERE parent_id = :parent_id)';
    const SQL_MAKE_SOME_SUB_VISIBLE = 'UPDATE parcel SET is_visible = 1, modified_date = :modified_date WHERE id IN (SELECT child_id FROM linked_parcel WHERE parent_id = :parent_id AND child_id IN (:child_id));';
    const SQL_DELETE_SOME_LINKAGE = 'DELETE FROM linked_parcel WHERE parent_id = :parent_id AND child_id IN (:child_id);';
    const SQL_DELETE_LINKAGE = 'DELETE FROM linked_parcel WHERE parent_id = :parent_id';
    const SQL_UPDATE_SUBS = 'UPDATE parcel SET from_branch_id = :from_branch_id, to_branch_id = :to_branch_id, `status` = :status, modified_date = :modified_date WHERE id IN (SELECT child_id FROM linked_parcel WHERE parent_id = :parent_id)';

    const QTY_METRICS_PIECES = 'pieces';
    const QTY_METRICS_WEIGHT = 'weight';

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     * @var int
     */
    protected $entity_type;

    /**
     * @var int
     */
    protected $is_visible;

    /**
     * @var int
     */
    protected $created_by;

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
    protected $package_value;

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
    protected $pos_trans_id;

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
     *
     * @var integer
     */
    protected $bank_account_id;

    /**
     * @var integer
     */
    protected $is_billing_overridden;

    /**
     * @var string
     */
    protected $reference_number;

    /**
     * @var integer
     */
    protected $created_branch_id;


    /**
     * @var string
     */
    protected $seal_id;

    /**
     * @var integer
     */
    protected $route_id;

    /**
     * @var integer
     */
    protected $request_type;

    /**
     * @var integer
     */
    protected $for_return;

    /**
     * @var integer
     */
    protected $weight_billing_plan_id;

    /**
     * @var integer
     */
    protected $onforwarding_billing_plan_id;

    /**
     * @var string
     */
    protected $billing_type;


    /**
     * @var integer
     */
    protected $is_freight_included;

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
     * Method to set the value of field entity_type
     *
     * @param integer $entity_type
     * @return $this
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;

        return $this;
    }

    /**
     * Method to set the value of field is_visible
     *
     * @param integer $is_visible
     * @return $this
     */
    public function setIsVisible($is_visible)
    {
        $this->is_visible = $is_visible;

        return $this;
    }

    /**
     * Method to set the value of field created_by
     *
     * @param integer $created_by
     * @return $this
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;

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
     * Method to set the value of field pos_trans_id
     *
     * @param string $pos_trans_id
     * @return $this
     */
    public function setPosTransId($pos_trans_id)
    {
        $this->pos_trans_id = $pos_trans_id;

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
     * Method to set the value of field bank_account_id
     *
     * @param string $bank_account_id
     * @return $this
     */
    public function setBankAccountId($bank_account_id)
    {
        $this->bank_account_id = $bank_account_id;

        return $this;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $is_billing_overridden
     */
    public function setIsBillingOverridden($is_billing_overridden)
    {
        $this->is_billing_overridden = $is_billing_overridden;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param string $reference_number
     */
    public function setReferenceNumber($reference_number)
    {
        $this->reference_number = $reference_number;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param int $created_branch_id
     */
    public function setCreatedBranchId($created_branch_id)
    {
        $this->created_branch_id = $created_branch_id;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param int $route_id
     */
    public function setRouteId($route_id)
    {
        $this->route_id = $route_id;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param int $request_type
     */
    public function setRequestType($request_type)
    {
        $this->request_type = $request_type;
    }

    /**
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param int $for_return
     */
    public function setForReturn($for_return)
    {
        $this->for_return = $for_return;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param int $weight_billing_plan_id
     */
    public function setWeightBillingPlanId($weight_billing_plan_id)
    {
        $this->weight_billing_plan_id = $weight_billing_plan_id;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param int $onforwarding_billing_plan_id
     */
    public function setOnforwardingBillingPlanId($onforwarding_billing_plan_id)
    {
        $this->onforwarding_billing_plan_id = $onforwarding_billing_plan_id;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param string $billing_type
     */
    public function setBillingType($billing_type)
    {
        $this->billing_type = $billing_type;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param int $is_freight_included
     */
    public function setIsFreightIncluded($is_freight_included)
    {
        $this->is_freight_included = $is_freight_included;
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
     * Returns the value of field entity_type
     *
     * @return integer
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * Returns the value of field is_visible
     *
     * @return integer
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * Returns the value of field created_by
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
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
     * Returns the value of field pos_trans_id
     *
     * @return string
     */
    public function getPosTransId()
    {
        return $this->pos_trans_id;
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
     * Returns the value of field bank_account_id
     *
     * @return string
     */
    public function getBankAccountId()
    {
        return $this->bank_account_id;
    }

    /**
     * Returns the value of field is_billing_overridden
     *
     * @return integer
     */
    public function getIsBillingOverridden()
    {
        return $this->is_billing_overridden;
    }

    /**
     * Returns the value of field reference_number
     *
     * @return integer
     */
    public function getReferenceNumber()
    {
        return $this->reference_number;
    }

    /**
     * Returns the value of field created_branch_id
     *
     * @return integer
     */
    public function getCreatedBranchId()
    {
        return $this->created_branch_id;
    }

    /**
     * @return string
     */
    public function getSealId()
    {
        return $this->seal_id;
    }

    /**
     * @param string $seal_id
     */
    public function setSealId($seal_id)
    {
        $this->seal_id = $seal_id;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return int
     */
    public function getRouteId()
    {
        return $this->route_id;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return int
     */
    public function getRequestType()
    {
        return $this->request_type;
    }

    /**
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return int $for_return
     */
    public function getForReturn()
    {
        return $this->for_return;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return int
     */
    public function getWeightBillingPlanId()
    {
        return $this->weight_billing_plan_id;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return int
     */
    public function getOnforwardingBillingPlanId()
    {
        return $this->onforwarding_billing_plan_id;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return string
     */
    public function getBillingType()
    {
        return $this->billing_type;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return int
     */
    public function getIsFreightIncluded()
    {
        return $this->is_freight_included;
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
        $this->belongsTo('bank_account_id', 'Bank_Account', 'id', array('alias' => 'Bank_Account'));
        $this->belongsTo('created_branch_id', 'Branch', 'id', array('alias' => 'CreatedBranch'));
        $this->belongsTo('route_id', 'Route', 'id', array('alias' => 'Route'));
        $this->hasManyToMany('id', 'LinkedParcel', 'parent_id', 'child_id', 'Parcel', 'id', ['alias' => 'Children']);
        $this->belongsTo('request_type', 'Request_type', 'id', array('alias' => 'RequestType'));
        $this->hasMany('waybill_number', 'DeliveryReceipt', 'waybill_number', array('alias' => 'DeliveryReceipts'));
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
            'entity_type' => 'entity_type',
            'created_by' => 'created_by',
            'is_visible' => 'is_visible',
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
            'pos_trans_id' => 'pos_trans_id',
            'waybill_number' => 'waybill_number',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'bank_account_id' => 'bank_account_id',
            'is_billing_overridden' => 'is_billing_overridden',
            'reference_number' => 'reference_number',
            'created_branch_id' => 'created_branch_id',
            'seal_id' => 'seal_id',
            'route_id' => 'route_id',
            'request_type' => 'request_type',
            'for_return' => 'for_return',
            'billing_type' => 'billing_type',
            'onforwarding_billing_plan_id' => 'onforwarding_billing_plan_id',
            'weight_billing_plan_id' => 'weight_billing_plan_id',
            'is_freight_included' => 'is_freight_included',
            'qty_metrics' => 'qty_metrics'
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'entity_type' => $this->getEntityType(),
            'created_by' => $this->getCreatedBy(),
            'is_visible' => $this->getIsVisible(),
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
            'pos_trans_id' => $this->getPosTransId(),
            'waybill_number' => $this->getWaybillNumber(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'bank_account_id' => $this->getBankAccountId(),
            'is_billing_overridden' => $this->getIsBillingOverridden(),
            'reference_number' => $this->getReferenceNumber(),
            'seal_id' => $this->getSealId(),
            'created_branch_id' => $this->getCreatedBranchId(),
            'route_id' => $this->getRouteId(),
            'request_type' => $this->getRequestType(),
            'for_return' => $this->getForReturn(),
            'billing_type' => $this->getBillingType(),
            'is_freight_included' => $this->getIsFreightIncluded()
        );
    }

    public function initData($parcel_type, $sender_id, $sender_address_id, $receiver_id, $receiver_address_id,
                             $weight, $amount_due, $cash_on_delivery, $delivery_amount, $delivery_type, $payment_type,
                             $shipping_type, $from_branch_id, $to_branch_id, $status, $package_value, $no_of_package, $other_info, $cash_amount,
                             $pos_amount, $pos_trans_id, $created_by, $is_visible = 1, $entity_type = 1, $waybill_number = null, $bank_account_id = null, $is_billing_overridden = 0,
                             $reference_number = null, $route_id = null, $request_type = RequestType::OTHERS, $billing_type = null, $weight_billing_plan_id = null, $onforwarding_billing_plan_id = null, $is_freight_included = 0
    )
    {
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
        $this->setPosTransId($pos_trans_id);
        $this->setWaybillNumber(($waybill_number == null) ? uniqid() : $waybill_number);
        $this->setOtherInfo($other_info);
        $this->setPackageValue($package_value);
        $this->setNoOfPackage($no_of_package);
        $this->setCreatedBy($created_by);
        $this->setEntityType($entity_type);
        $this->setIsVisible($is_visible);
        $this->setBankAccountId($bank_account_id);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($this->getCreatedDate());
        $this->setStatus($status);
        $this->setIsBillingOverridden($is_billing_overridden);
        $this->setReferenceNumber($reference_number);
        $this->setCreatedBranchId($from_branch_id);
        $this->setRouteId($route_id);
        $this->setRequestType($request_type);
        $this->setForReturn(0);
        $this->setBillingType($billing_type);
        $this->setWeightBillingPlanId($weight_billing_plan_id);
        $this->setOnforwardingBillingPlanId($onforwarding_billing_plan_id);
        $this->setIsFreightIncluded($is_freight_included);
    }

    public function initDataWithBasicInfo($from_branch_id, $to_branch_id, $created_by, $status, $waybill_number, $entity_type, $is_visible)
    {
        $this->setParcelType(null);
        $this->setSenderId(null);
        $this->setSenderAddressId(null);
        $this->setReceiverId(null);
        $this->setReceiverAddressId(null);
        $this->setFromBranchId($from_branch_id);
        $this->setToBranchId($to_branch_id);
        $this->setWeight(null);
        $this->setAmountDue(null);
        $this->setCashOnDelivery(null);
        $this->setCashOnDeliveryAmount(null);
        $this->setDeliveryType(null);
        $this->setPaymentType(null);
        $this->setShippingType(null);
        $this->setCashAmount(null);
        $this->setPosAmount(null);
        $this->setPosTransId(null);
        $this->setWaybillNumber($waybill_number);
        $this->setOtherInfo("N/A");
        $this->setPackageValue(null);
        $this->setNoOfPackage(null);
        $this->setCreatedBy($created_by);
        $this->setEntityType($entity_type);
        $this->setIsVisible($is_visible);
        $this->setBankAccountId(null);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($this->getCreatedDate());
        $this->setStatus($status);
        $this->setIsBillingOverridden(0);
        $this->setReferenceNumber(null);
        $this->setCreatedBranchId($from_branch_id);
        $this->setRouteId(null);
        $this->setRequestType(RequestType::OTHERS);
        $this->setForReturn(0);
        $this->setBillingType(null);
        $this->setWeightBillingPlanId(null);
        $this->setOnforwardingBillingPlanId(null);
        $this->setIsFreightIncluded(0);
    }

    private function getEntityTypeLabel()
    {
        $entity_type_label = "X";
        switch ($this->getEntityType()) {
            case self::ENTITY_TYPE_BAG:
                $entity_type_label = "B";
                break;
            case self::ENTITY_TYPE_PARENT:
                $entity_type_label = "S";
                break;
            case self::ENTITY_TYPE_NORMAL:
                $entity_type_label = "N";
                break;
            default:
                break;
        }
        return $entity_type_label;
    }

    public function generateWaybillNumber($initial_branch_id)
    {
        $entity_type_label = $this->getEntityTypeLabel();
        $waybill_number = $this->getDeliveryType()
            . $entity_type_label
            . str_pad($initial_branch_id, 3, '0', STR_PAD_LEFT)
            . str_pad($this->getId(), 8, '0', STR_PAD_LEFT);

        $this->setWaybillNumber($waybill_number);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchOne($fetch_value, $in_recursion = false, $fetch_by = 'id')
    {
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Parcel.*', 'Sender.*', 'Receiver.*', 'SenderAddress.*', 'ReceiverAddress.*', 'CreatedBranch.*', 'FromBranch.*', 'ToBranch.*'])
            ->from('Parcel')
            ->leftJoin('Sender', 'Sender.id = Parcel.sender_id', 'Sender')
            ->leftJoin('Receiver', 'Receiver.id = Parcel.receiver_id', 'Receiver')
            ->leftJoin('SenderAddress', 'SenderAddress.id = Parcel.sender_address_id', 'SenderAddress')
            ->leftJoin('ReceiverAddress', 'ReceiverAddress.id = Parcel.receiver_address_id', 'ReceiverAddress')
            ->leftJoin('CreatedBranch', 'CreatedBranch.id = Parcel.created_branch_id', 'CreatedBranch')
            ->leftJoin('FromBranch', 'FromBranch.id = Parcel.from_branch_id', 'FromBranch')
            ->leftJoin('ToBranch', 'ToBranch.id = Parcel.to_branch_id', 'ToBranch');
        $builder = $builder->where("Parcel.$fetch_by = :$fetch_by:", [$fetch_by => $fetch_value]);


        $data = $builder->getQuery()->execute();
        if (count($data) == 0) return false;

        $result = $data[0]->parcel->getData();
        $result['sender'] = $data[0]->sender->getData();
        $result['sender_address'] = $data[0]->senderAddress->getData();
        $result['receiver'] = $data[0]->receiver->getData();
        $result['receiver_address'] = $data[0]->receiverAddress->getData();
        $result['created_branch'] = $data[0]->createdBranch->getData();
        $result['to_branch'] = $data[0]->toBranch->getData();
        $result['from_branch'] = $data[0]->fromBranch->getData();

        if (!$in_recursion) {
            $linkage = LinkedParcel::getByChildId($result['id']);
            if ($linkage != false) {
                $result['parent'] = Parcel::fetchOne($linkage->getParentId(), true);
            } else {
                $result['parent'] = null;
            }

            $result['parcels'] = $data[0]->parcel->getChildren()->toArray();
        }

        return $result;
    }

    /**
     * Adds filter conditions
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     * @author Rahman Shitu <goke@cottacush.com>
     * @param $filter_by
     * @return array
     */
    private static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];

        //filters
        if (isset($filter_by['held_by_id'])) {
            $where[] = 'HeldParcel.held_by_id = :held_by_id: AND HeldParcel.status = :held_status:';
            $bind['held_by_id'] = $filter_by['held_by_id'];
            $bind['held_status'] = Status::PARCEL_UNCLEARED;
        } else if (isset($filter_by['held_by_staff_id'])) {
            $where[] = 'Admin.staff_id = :held_by_staff_id: AND HeldParcel.status = :held_status:';
            $bind['held_by_staff_id'] = $filter_by['held_by_staff_id'];
            $bind['held_status'] = Status::PARCEL_UNCLEARED;
        } else if (isset($filter_by['manifest_id'])) {
            $where[] = 'HeldParcel.manifest_id = :manifest_id:';
            $bind['manifest_id'] = $filter_by['manifest_id'];
        }

        $bind['is_visible'] = (isset($filter_by['is_visible'])) ? $filter_by['is_visible'] : 1;

        $initial_cond = 'Parcel.is_visible = :is_visible:';
        if (isset($filter_by['show_parents'])) {
            $initial_cond = '(Parcel.is_visible = :is_visible: OR Parcel.entity_type = ' . self::ENTITY_TYPE_PARENT . ') AND Parcel.entity_type != ' . self::ENTITY_TYPE_SUB;
        }
        $where[] = $initial_cond;

        if (isset($filter_by['parent_id'])) {
            $where[] = 'LinkedParcel.parent_id = :parent_id:';
            $bind['parent_id'] = $filter_by['parent_id'];
        }
        if (isset($filter_by['entity_type'])) {
            $where[] = 'Parcel.entity_type = :entity_type:';
            $bind['entity_type'] = $filter_by['entity_type'];
        }
        if (isset($filter_by['created_by'])) {
            $where[] = 'Parcel.created_by = :created_by:';
            $bind['created_by'] = $filter_by['created_by'];
        }
        if (isset($filter_by['user_id'])) {
            $where[] = '(Parcel.sender_id = :user_id: OR Parcel.receiver_id = :user_id:)';
            $bind['user_id'] = $filter_by['user_id'];
        }
        if (isset($filter_by['to_branch_id'])) {
            $where[] = 'Parcel.to_branch_id = :to_branch_id:';
            $bind['to_branch_id'] = $filter_by['to_branch_id'];
        }
        if (isset($filter_by['from_branch_id'])) {
            $where[] = 'Parcel.from_branch_id = :from_branch_id:';
            $bind['from_branch_id'] = $filter_by['from_branch_id'];
        }
        if (isset($filter_by['parcel_type'])) {
            $where[] = 'Parcel.parcel_type = :parcel_type:';
            $bind['parcel_type'] = $filter_by['parcel_type'];
        }
        if (isset($filter_by['sender_id'])) {
            $where[] = 'Parcel.sender_id = :sender_id:';
            $bind['sender_id'] = $filter_by['sender_id'];
        }
        if (isset($filter_by['sender_address_id'])) {
            $where[] = 'Parcel.sender_address_id = :sender_address_id:';
            $bind['sender_address_id'] = $filter_by['sender_address_id'];
        }
        if (isset($filter_by['receiver_id'])) {
            $where[] = 'Parcel.receiver_id = :receiver_id:';
            $bind['receiver_id'] = $filter_by['receiver_id'];
        }
        if (isset($filter_by['receiver_address_id'])) {
            $where[] = 'Parcel.receiver_address_id = :receiver_address_id:';
            $bind['receiver_address_id'] = $filter_by['receiver_address_id'];
        }
        if (isset($filter_by['status'])) {
            $where[] = 'Parcel.status = :status:';
            $bind['status'] = $filter_by['status'];
        }
        if (isset($filter_by['min_weight'])) {
            $where[] = 'Parcel.weight >= :min_weight:';
            $bind['min_weight'] = $filter_by['min_weight'];
        }
        if (isset($filter_by['max_weight'])) {
            $where[] = 'Parcel.weight <= :max_weight:';
            $bind['max_weight'] = $filter_by['max_weight'];
        }
        if (isset($filter_by['min_amount_due'])) {
            $where[] = 'Parcel.amount_due >= :min_weight:';
            $bind['min_amount_due'] = $filter_by['min_amount_due'];
        }
        if (isset($filter_by['max_amount_due'])) {
            $where[] = 'Parcel.amount_due <= :max_weight:';
            $bind['max_amount_due'] = $filter_by['max_amount_due'];
        }
        if (isset($filter_by['cash_on_delivery'])) {
            $where[] = 'Parcel.cash_on_delivery = :cash_on_delivery:';
            $bind['cash_on_delivery'] = $filter_by['cash_on_delivery'];
        }
        if (isset($filter_by['min_delivery_amount'])) {
            $where[] = 'Parcel.delivery_amount >= :min_delivery_amount:';
            $bind['min_delivery_amount'] = $filter_by['min_delivery_amount'];
        }
        if (isset($filter_by['max_delivery_amount'])) {
            $where[] = 'Parcel.delivery_amount <= :max_delivery_amount:';
            $bind['max_delivery_amount'] = $filter_by['max_delivery_amount'];
        }
        if (isset($filter_by['delivery_type'])) {
            $where[] = 'Parcel.delivery_type = :delivery_type:';
            $bind['delivery_type'] = $filter_by['delivery_type'];
        }
        if (isset($filter_by['payment_type'])) {
            $where[] = 'Parcel.payment_type = :payment_type:';
            $bind['payment_type'] = $filter_by['payment_type'];
        }
        if (isset($filter_by['shipping_type'])) {
            $where[] = 'Parcel.shipping_type = :shipping_type:';
            $bind['shipping_type'] = $filter_by['shipping_type'];
        }
        if (isset($filter_by['min_cash_amount'])) {
            $where[] = 'Parcel.cash_amount >= :min_cash_amount:';
            $bind['min_cash_amount'] = $filter_by['min_cash_amount'];
        }
        if (isset($filter_by['max_cash_amount'])) {
            $where[] = 'Parcel.cash_amount <= :max_cash_amount:';
            $bind['max_cash_amount'] = $filter_by['max_cash_amount'];
        }
        if (isset($filter_by['min_pos_amount'])) {
            $where[] = 'Parcel.pos_amount >= :min_pos_amount:';
            $bind['min_pos_amount'] = $filter_by['min_pos_amount'];
        }
        if (isset($filter_by['max_pos_amount'])) {
            $where[] = 'Parcel.pos_amount <= :max_pos_amount:';
            $bind['max_pos_amount'] = $filter_by['max_pos_amount'];
        }
        if (isset($filter_by['start_created_date'])) {
            $where[] = 'Parcel.created_date >= :start_created_date:';
            $bind['start_created_date'] = $filter_by['start_created_date'];
        }
        if (isset($filter_by['end_created_date'])) {
            $where[] = 'Parcel.created_date <= :end_created_date:';
            $bind['end_created_date'] = $filter_by['end_created_date'];
        }
        if (isset($filter_by['start_modified_date'])) {
            $where[] = 'Parcel.modified_date >= :start_modified_date:';
            $bind['start_modified_date'] = $filter_by['start_modified_date'];
        }
        if (isset($filter_by['end_modified_date'])) {
            $where[] = 'Parcel.modified_date <= :end_modified_date:';
            $bind['end_modified_date'] = $filter_by['end_modified_date'];
        }
        if (isset($filter_by['waybill_number'])) {
            $where[] = 'Parcel.waybill_number LIKE :waybill_number:';
            $bind['waybill_number'] = '%' . $filter_by['waybill_number'] . '%';
        }
        if (isset($filter_by['route_id'])) {
            $where[] = 'Parcel.route_id = :route_id:';
            $bind['route_id'] = $filter_by['route_id'];
        }
        if (isset($filter_by['history_status'])) {
            $where[] = 'ParcelHistory.status = :history_status:';
            $bind['history_status'] = $filter_by['history_status'];
        }
        if (isset($filter_by['history_start_created_date'])) {
            $where[] = 'ParcelHistory.created_date >= :history_start_created_date:';
            $bind['history_start_created_date'] = $filter_by['history_start_created_date'];
        }
        if (isset($filter_by['history_end_created_date'])) {
            $where[] = 'ParcelHistory.created_date <= :history_end_created_date:';
            $bind['history_end_created_date'] = $filter_by['history_end_created_date'];
        }
        if (isset($filter_by['history_from_branch_id'])) {
            $where[] = 'ParcelHistory.from_branch_id <= :history_from_branch_id:';
            $bind['history_from_branch_id'] = $filter_by['history_from_branch_id'];
        }
        if (isset($filter_by['history_to_branch_id'])) {
            $where[] = 'ParcelHistory.to_branch_id <= :history_to_branch_id:';
            $bind['history_to_branch_id'] = $filter_by['history_to_branch_id'];
        }
        if (isset($filter_by['created_branch_id'])) {
            $where[] = 'Parcel.created_branch_id = :created_branch_id:';
            $bind['created_branch_id'] = $filter_by['created_branch_id'];
        }
        if (isset($filter_by['request_type'])) {
            $where[] = 'Parcel.request_type = :request_type:';
            $bind['request_type'] = $filter_by['request_type'];
        }
        if (isset($filter_by['for_return'])) {
            $where[] = 'Parcel.for_return = :for_return:';
            $bind['for_return'] = $filter_by['for_return'];
        }

        if (isset($filter_by['billing_type'])) {
            $where[] = 'Parcel.billing_type = :billing_type:';
            $bind['billing_type'] = $filter_by['billing_type'];
        }

        if (isset($filter_by['company_id'])) {
            $where[] = 'Company.id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        if(isset($filter_by['freight_included'])) {
            $where[] = 'Parcel.is_freight_included = :freight_included:';
            $bind['freight_included'] = $filter_by['freight_included'];
        }

        return ['where' => $where, 'bind' => $bind];
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with, $order_by_clause = null)
    {
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Parcel');

        if (!isset($filter_by['send_all'])) {
            $builder->limit($count, $offset);
        }

        $columns = ['Parcel.*'];

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        if ($order_by_clause != null) {
            $builder->orderBy($order_by_clause);
        } else if (isset($filter_by['start_modified_date']) or isset($filter_by['end_modified_date'])) {
            $builder->orderBy('Parcel.modified_date');
        } else {
            $builder->orderBy('Parcel.id');
        }

        if (isset($filter_by['parent_id'])) {
            $builder->innerJoin('LinkedParcel', 'LinkedParcel.child_id = Parcel.id');
        }

        if (isset($filter_by['held_by_id']) or isset($filter_by['manifest_id'])) {
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id');
        } else if (isset($filter_by['held_by_staff_id'])) {
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id');
            $builder->innerJoin('Admin', 'Admin.id = HeldParcel.held_by_id');
        }
        if (isset($filter_by['history_status']) or isset($filter_by['history_from_branch_id']) or isset($filter_by['history_to_branch_id']) or isset($filter_by['history_start_created_date']) or isset($filter_by['history_end_created_date'])) {
            $builder->innerJoin('ParcelHistory', 'ParcelHistory.parcel_id = Parcel.id');
            $builder->groupBy('Parcel.id');
        }

        //model hydration
        if (isset($fetch_with['with_to_branch'])) {
            $columns[] = 'ToBranch.*';
            $builder->innerJoin('ToBranch', 'ToBranch.id = Parcel.to_branch_id', 'ToBranch');
            $columns[] = 'ToBranchState.*';
            $builder->innerJoin('ToBranchState', 'ToBranchState.id = ToBranch.state_id', 'ToBranchState');
        }
        if (isset($fetch_with['with_from_branch'])) {
            $columns[] = 'FromBranch.*';
            $builder->innerJoin('FromBranch', 'FromBranch.id = Parcel.from_branch_id', 'FromBranch');
            $columns[] = 'FromBranchState.*';
            $builder->innerJoin('FromBranchState', 'FromBranchState.id = FromBranch.state_id', 'FromBranchState');
        }
        if (isset($fetch_with['with_sender_address'])) {
            $columns[] = 'SenderAddress.*';
            $builder->leftJoin('SenderAddress', 'SenderAddress.id = Parcel.sender_address_id', 'SenderAddress');
            $columns[] = 'SenderAddressState.*';
            $builder->leftJoin('SenderAddressState', 'SenderAddressState.id = SenderAddress.state_id', 'SenderAddressState');
            $columns[] = 'SenderAddressCity.*';
            $builder->leftJoin('SenderAddressCity', 'SenderAddressCity.id = SenderAddress.city_id', 'SenderAddressCity');
        }
        if (isset($fetch_with['with_receiver_address'])) {
            $columns[] = 'ReceiverAddress.*';
            $builder->leftJoin('ReceiverAddress', 'ReceiverAddress.id = Parcel.receiver_address_id', 'ReceiverAddress');
            $columns[] = 'ReceiverAddressState.*';
            $builder->leftJoin('ReceiverAddressState', 'ReceiverAddressState.id = ReceiverAddress.state_id', 'ReceiverAddressState');
            $columns[] = 'ReceiverAddressCity.*';
            $builder->leftJoin('ReceiverAddressCity', 'ReceiverAddressCity.id = ReceiverAddress.city_id', 'ReceiverAddressCity');
        }
        if (isset($fetch_with['with_holder'])) {
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id AND HeldParcel.status = ' . Status::PARCEL_UNCLEARED);
            $builder->innerJoin('Admin', 'Admin.id = HeldParcel.held_by_id');
            $columns[] = 'Admin.*';
        }
        if (isset($fetch_with['with_bank_account'])) {
            $columns[] = 'BankAccount.*';
            $builder->leftJoin('BankAccount', 'BankAccount.id = Parcel.bank_account_id');
            $columns[] = 'Bank.*';
            $builder->leftJoin('Bank', 'Bank.id = BankAccount.bank_id');
        }
        if (isset($fetch_with['with_created_branch'])) {
            $columns[] = 'CreatedBranch.*';
            $builder->leftJoin('CreatedBranch', 'CreatedBranch.id = Parcel.created_branch_id', 'CreatedBranch');
            $columns[] = 'CreatedBranchState.*';
            $builder->leftJoin('CreatedBranchState', 'CreatedBranchState.id = CreatedBranch.state_id', 'CreatedBranchState');
        }
        if (isset($fetch_with['with_created_by'])) {
            $columns[] = 'CreatedBy.*';
            $builder->innerJoin('CreatedBy', 'CreatedBy.id = Parcel.created_by', 'CreatedBy');
        }
        if (isset($fetch_with['with_route'])) {
            $columns[] = 'Routes.*';
            $builder->leftJoin('Route', 'Routes.id = Parcel.route_id', 'Routes');
        }

        if (isset($fetch_with['with_sender'])) {
            $columns[] = 'Sender.*';
            $builder->leftJoin('Sender', 'Sender.id = Parcel.sender_id', 'Sender');
        }
        if (isset($fetch_with['with_receiver'])) {
            $columns[] = 'Receiver.*';
            $builder->leftJoin('Receiver', 'Receiver.id = Parcel.receiver_id', 'Receiver');
        }
        if (isset($fetch_with['with_delivery_receipt'])) {
            $columns[] = 'DeliveryReceipt.*';
            $builder->leftJoin('DeliveryReceipt', 'DeliveryReceipt.waybill_number = Parcel.waybill_number', 'DeliveryReceipt');
            $builder->groupBy('Parcel.waybill_number');
        }

        if (isset($fetch_with['with_payment_type'])) {
            $columns[] = 'PaymentType.*';
            $builder->innerJoin('PaymentType', 'PaymentType.id = Parcel.payment_type', 'PaymentType');
        }

        $builder->where(join(' AND ', $where));

        if (isset($filter_by['waybill_number_arr'])) {
            $waybill_number_arr = explode(',', $filter_by['waybill_number_arr']);

            $builder->inWhere('Parcel.waybill_number', $waybill_number_arr);
        }

        if (isset($fetch_with['with_company'])) {
            $columns[] = 'Company.*';
            $builder->innerJoin('BillingPlan', 'BillingPlan.id= Parcel.onforwarding_billing_plan_id');
            $builder->innerJoin('Company', 'Company.id = BillingPlan.company_id');
        }

        if (isset($fetch_with['with_invoice_parcel'])) {
            $columns[] = 'InvoiceParcel.*';
            $builder->leftJoin('InvoiceParcel', 'InvoiceParcel.waybill_number= Parcel.waybill_number');
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            $parcel = [];
            if (!property_exists($item, 'parcel')) {
                $parcel = $item->getData();
            } else {
                $parcel = $item->parcel->getData();
                if (isset($fetch_with['with_holder'])) {
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
                    $parcel['sender_address']['city'] = $item->senderAddressCity->getData();
                }
                if (isset($fetch_with['with_receiver'])) $parcel['receiver'] = $item->receiver->getData();
                if (isset($fetch_with['with_receiver_address'])) {
                    $parcel['receiver_address'] = $item->receiverAddress->getData();
                    $parcel['receiver_address']['state'] = $item->receiverAddressState->getData();
                    $parcel['receiver_address']['city'] = $item->receiverAddressCity->getData();
                }
                if (isset($fetch_with['with_bank_account'])) {
                    $parcel['bank_account'] = $item->bankAccount->getData();
                    $parcel['bank_account']['bank'] = $item->bank->getData();
                }
                if (isset($fetch_with['with_created_branch'])) {
                    $parcel['created_branch'] = $item->createdBranch->getData();
                    $parcel['created_branch']['state'] = $item->createdBranchState->getData();
                }
                if (isset($fetch_with['with_route'])) {
                    $parcel['route'] = $item->Routes->getData();
                }
                if (isset($fetch_with['with_created_by'])) {
                    $parcel['created_by'] = $item->createdBy->getData();
                }
                if (isset($fetch_with['with_delivery_receipt'])) {
                    $parcel['delivery_receipt'] = $item->deliveryReceipt->toArray();
                }
                if (isset($fetch_with['with_payment_type'])) {
                    $parcel['payment_type'] = $item->paymentType->toArray();
                }
                if (isset($fetch_with['with_company'])) {
                    $parcel['company'] = $item->company->toArray();
                }
                if (isset($fetch_with['with_invoice_parcel'])) {
                    $parcel['invoice_parcel'] = $item->invoiceParcel->toArray();
                }
            }
            $result[] = $parcel;
        }
        return $result;
    }

    public static function parcelCount($filter_by)
    {
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS parcel_count')
            ->from('Parcel');

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        if (isset($filter_by['parent_id'])) {
            $builder->innerJoin('LinkedParcel', 'LinkedParcel.child_id = Parcel.id');
        }

        if (isset($filter_by['held_by_id']) or isset($filter_by['manifest_id'])) {
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id');
        } else if (isset($filter_by['held_by_staff_id'])) {
            $builder->innerJoin('HeldParcel', 'HeldParcel.parcel_id = Parcel.id');
            $builder->innerJoin('Admin', 'Admin.id = HeldParcel.held_by_id');
        }
        if (isset($filter_by['history_status']) or isset($filter_by['history_from_branch_id']) or isset($filter_by['history_to_branch_id']) or isset($filter_by['history_start_created_date']) or isset($filter_by['history_end_created_date'])) {
            $builder->innerJoin('ParcelHistory', 'ParcelHistory.parcel_id = Parcel.id');
            $builder->columns('COUNT(DISTINCT Parcel.id) AS parcel_count');
        }

        if (isset($filter_by['company_id'])) {
            $builder->innerJoin('BillingPlan', 'BillingPlan.id= Parcel.onforwarding_billing_plan_id');
            $builder->innerJoin('Company', 'Company.id = BillingPlan.company_id');
        }

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
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
    public function saveForm($from_branch_id, $sender, $sender_address, $receiver, $receiver_address, $bank_account, $parcel_data, $to_branch_id, $admin_id)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $check = true;
            $this->setTransaction($transaction);

            //saving the sender's user info
            $sender_obj = User::fetchByPhone($sender['phone']);
            $is_sender_existing = $sender_obj != false;
            if (!$is_sender_existing) {
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
                $is_receiver_existing = $receiver_obj != false && $receiver['phone'] != self::NOT_APPLICABLE;
                if (!$is_receiver_existing) {
                    $receiver_obj = new User();
                }
                $receiver_obj->setTransaction($transaction);
                $receiver_obj->initData($receiver['firstname'], $receiver['lastname'], $receiver['phone'], $receiver['email'], null, $is_receiver_existing);
                $check = $receiver_obj->save();
            } else {
                Util::slackDebug("Parcel not created", "Unable to save sender's info");
                Util::slackDebug("Parcel not created", $sender_obj->getMessages());
                return false;
            }

            //saving the sender's address
            $sender_addr_obj = new Address();
            $is_existing = false;
            if ($check) {
                if ($sender_address['id'] != null) {
                    $sender_addr_obj = Address::fetchById($sender_address['id']);
                    $is_existing = ($sender_addr_obj != false);
                    if (!$is_existing) {
                        $sender_addr_obj = new Address();
                    } else {
                        if (!Address::isSame($sender_addr_obj, $sender_address)) {
                            $sender_addr_obj = new Address();
                            $is_existing = false;
                        }
                    }
                }
                if ($is_existing and ($sender_addr_obj->getOwnerId() != $sender_obj->getId() OR $sender_addr_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)) {
                    Util::slackDebug("Parcel not created", "Sender address id not for sender");
                    $transactionManager->rollback();
                    return false;
                }
                $sender_addr_obj->setTransaction($transaction);
                $sender_addr_obj->initData($sender_obj->getId(), OWNER_TYPE_CUSTOMER,
                    $sender_address['street1'], $sender_address['street2'], intval($sender_address['state_id']),
                    intval($sender_address['country_id']), $sender_address['city_id'], $is_existing, $is_sender_existing);

                $check = $sender_addr_obj->save();
            } else {
                Util::slackDebug("Parcel not created", "Unable to save receiver's info");
                Util::slackDebug("Parcel not created", $receiver_obj->getMessages());
                return false;
            }

            //saving the receiver's address
            $receiver_addr_obj = new Address();
            $is_existing = false;
            if ($check) {
                if ($receiver_address['id'] != null) {
                    $receiver_addr_obj = Address::fetchById($receiver_address['id']);
                    $is_existing = ($receiver_addr_obj != false);
                    if (!$is_existing) {
                        $receiver_addr_obj = new Address();
                    } else {
                        if (!Address::isSame($receiver_addr_obj, $receiver_address)) {
                            $receiver_addr_obj = new Address();
                            $is_existing = false;
                        }
                    }
                }
                if ($is_existing and ($receiver_addr_obj->getOwnerId() != $receiver_obj->getId() OR $receiver_addr_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)) {
                    Util::slackDebug("Parcel not created", "Receiver address id not for receiver");
                    $transactionManager->rollback();
                    return false;
                }
                $receiver_addr_obj->setTransaction($transaction);
                $receiver_addr_obj->initData($receiver_obj->getId(), OWNER_TYPE_CUSTOMER,
                    $receiver_address['street1'], $receiver_address['street2'], $receiver_address['state_id'],
                    $receiver_address['country_id'], $receiver_address['city_id'], $is_existing, $is_receiver_existing);
                $check = $receiver_addr_obj->save();
            } else {
                Util::slackDebug("Parcel not created", "Unable to save sender's address");
                Util::slackDebug("Parcel not created", $sender_addr_obj->getMessages());
                return false;
            }

            //saving a bank account if any was provided
            $bank_account_obj = new BankAccount();
            $is_existing = false;
            if ($bank_account != null) {
                if ($check) {
                    if ($bank_account['id'] != null) {
                        $bank_account_obj = BankAccount::fetchById($bank_account['id']);
                        $is_existing = ($bank_account_obj != false);
                        if ($is_existing) {
                            $bank_account_obj = new BankAccount();
                        }
                    }
                    if ($is_existing and ($bank_account_obj->getOwnerId() != $sender_obj->getId() OR $bank_account_obj->getOwnerType() != OWNER_TYPE_CUSTOMER)) {
                        $transactionManager->rollback();
                        return false;
                    }
                    $bank_account_obj->setTransaction($transaction);
                    $bank_account_obj->initData($sender_obj->getId(), OWNER_TYPE_CUSTOMER, $bank_account['bank_id'],
                        $bank_account['account_name'], $bank_account['account_no'], $bank_account['sort_code'], $is_existing);
                    $check = $bank_account_obj->save();
                } else {
                    Util::slackDebug("Parcel not created", "Unable to save receiver's address");
                    Util::slackDebug("Parcel not created", $receiver_addr_obj->getMessages());
                    return false;
                }
            }


            //finally saving the parcel
            $parcel_status = ($to_branch_id == $from_branch_id) ? Status::PARCEL_FOR_DELIVERY : Status::PARCEL_FOR_SWEEPER;
            $is_visible = ($parcel_data['no_of_package'] > 1) ? 0 : 1; //hide parcel from view if it is a parent to split parcels.
            $entity_type = ($parcel_data['no_of_package'] > 1) ? self::ENTITY_TYPE_PARENT : self::ENTITY_TYPE_NORMAL;
            if ($check) {
                $this->initData($parcel_data['parcel_type'], $sender_obj->getId(), $sender_addr_obj->getId(),
                    $receiver_obj->getId(), $receiver_addr_obj->getId(), $parcel_data['weight'], $parcel_data['amount_due'],
                    $parcel_data['cash_on_delivery'], $parcel_data['cash_on_delivery_amount'], $parcel_data['delivery_type'],
                    $parcel_data['payment_type'], $parcel_data['shipping_type'], $from_branch_id, $to_branch_id, $parcel_status,
                    $parcel_data['package_value'], $parcel_data['no_of_package'], $parcel_data['other_info'], $parcel_data['cash_amount'],
                    $parcel_data['pos_amount'], $parcel_data['pos_trans_id'], $admin_id, $is_visible, $entity_type, null, $bank_account_obj->getId(),
                    $parcel_data['is_billing_overridden'], $parcel_data['reference_number'], null, $parcel_data['request_type'],
                    $parcel_data['billing_type'], $parcel_data['weight_billing_plan'], $parcel_data['onforwarding_billing_plan'], $parcel_data['is_freight_included']);
                $check = $this->save();
            } else {
                if ($bank_account != null) {
                    Util::slackDebug("Parcel not created", "Unable to save bank account");
                    Util::slackDebug("Parcel not created", $bank_account_obj->getMessages());
                    return false;
                } else {
                    Util::slackDebug("Parcel not created", "Unable to save receiver's address");
                    Util::slackDebug("Parcel not created", $receiver_addr_obj->getMessages());
                    return false;
                }
            }

            //setting waybill number
            if ($check) {
                $this->generateWaybillNumber($from_branch_id);
                $check = $this->save();
            } else {
                Util::slackDebug("Parcel not created", "Unable to save parcel");
                Util::slackDebug("Parcel not created", var_export($this->getMessages(), true));
            }
            $waybill_number = [$this->getWaybillNumber()];

            //creating sub-parcel if the number of packages is more than 1
            if ($check and $this->getNoOfPackage() > 1) {
                $waybill_number = $this->createSub($transaction);
                $check = $waybill_number != false;
            }

            //saving the parcel history
            if ($check) {
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $history_desc = ($to_branch_id == $from_branch_id) ? ParcelHistory::MSG_FOR_DELIVERY : ParcelHistory::MSG_FOR_SWEEPER;
                $parcel_history->initData($this->getId(), $from_branch_id, $history_desc, $admin_id, $parcel_status, $to_branch_id);
                $check = $parcel_history->save();
            }

            if ($check) {
                if (is_null($this->getReferenceNumber())) {
                    $this->setReferenceNumber($this->getWaybillNumber());
                    if (!$this->save()) {
                        $transactionManager->rollback();
                        return false;
                    }
                }
                $transactionManager->commit();
                return $waybill_number;
            } else {
                Util::slackDebug("Parcel not created", "Unable to save parcel history");
                Util::slackDebug("Parcel not created", $parcel_history->getMessages());
                return false;
            }
        } catch (Exception $e) {
            Util::slackDebug("EXCEPTION LOG", $e->getMessage());
        }

        $transactionManager->rollback();
        return false;
    }

    public function changeStatus($status, $admin_id, $history_desc, $admin_branch_id, $alter_children = false)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->setStatus($status);
            $this->setModifiedDate(date('Y-m-d H:i:s'));
            $check = true;

            if ($this->save()) {
                if ($this->getEntityType() == Parcel::ENTITY_TYPE_BAG || $alter_children) {
                    $check = $this->alterSubs();
                }

                if (!$check) {
                    $transactionManager->rollback();
                    return false;
                }
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $parcel_history->initData($this->getId(), $admin_branch_id, $history_desc, $admin_id, $status, null);
                if ($parcel_history->save()) {
                    $transactionManager->commit();
                    return true;
                }
            }
        } catch (Exception $e) {
            Util::slackDebug("EXCEPTION LOG", $e->getMessage());
        }

        $transactionManager->rollback();
        return false;
    }

    public function changeDestination($status, $to_branch_id, $admin_id, $history_desc)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->setStatus($status);
            $this->setFromBranchId($this->getToBranchId());
            $this->setToBranchId($to_branch_id);
            $this->setModifiedDate(date('Y-m-d H:i:s'));
            $check = true;

            if ($this->save()) {
                if ($this->getEntityType() == Parcel::ENTITY_TYPE_BAG) {
                    $check = $this->alterSubs();
                }

                if (!$check) {
                    $transactionManager->rollback();
                    return false;
                }
                $parcel_history = new ParcelHistory();
                $parcel_history->setTransaction($transaction);
                $parcel_history->initData($this->getId(), $this->getFromBranchId(), $history_desc, $admin_id, $status, $this->getToBranchId());
                if ($parcel_history->save()) {
                    $transactionManager->commit();
                    return true;
                }
            }
        } catch (Exception $e) {
            Util::slackDebug("EXCEPTION LOG", $e->getMessage());
        }

        $transactionManager->rollback();
        return false;
    }

    public function checkout($status, $held_by_id, $admin_id, $history_desc, $manifest_id)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->setStatus($status);
            $this->setModifiedDate(date('Y-m-d H:i:s'));
            $check = true;

            if ($this->save()) {
                if ($this->getEntityType() == Parcel::ENTITY_TYPE_BAG) {
                    $check = $this->alterSubs();
                }

                if (!$check) {
                    $transactionManager->rollback();
                    return false;
                }
                $held_parcel = new HeldParcel();
                $held_parcel->setTransaction($transaction);
                $held_parcel->initData($this->getId(), $held_by_id, $manifest_id);
                if ($held_parcel->save()) {
                    $parcel_history = new ParcelHistory();
                    $parcel_history->setTransaction($transaction);
                    $parcel_history->initData($this->getId(), $this->getFromBranchId(), $history_desc, $admin_id, $status, $this->getToBranchId());
                    if ($parcel_history->save()) {
                        $transactionManager->commit();
                        return true;
                    }
                }
            }
        } catch (Exception $e) {
            Util::slackDebug("EXCEPTION LOG", $e->getMessage());
        }

        $transactionManager->rollback();
        return false;
    }

    /**
     * @param HeldParcel $held_parcel_record
     * @param int $admin_id
     * @param int $status
     * @return bool
     */
    public function checkIn($held_parcel_record, $admin_id, $status = Status::PARCEL_ARRIVAL, $message = ParcelHistory::MSG_FOR_ARRIVAL)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->setStatus($status);
            $this->setModifiedDate(date('Y-m-d H:i:s'));
            $check = true;

            if ($this->save()) {
                if ($this->getEntityType() == Parcel::ENTITY_TYPE_BAG) {
                    $check = $this->alterSubs();
                }

                if (!$check) {
                    $transactionManager->rollback();
                    return false;
                }
                if ($held_parcel_record != false) {
                    $held_parcel_record->setTransaction($transaction);
                    $held_parcel_record->clear();
                    if ($held_parcel_record->save()) {
                        $parcel_history = new ParcelHistory();
                        $parcel_history->setTransaction($transaction);
                        $parcel_history->initData($this->getId(), $this->getFromBranchId(), $message, $admin_id, $status, $this->getToBranchId());
                        if ($parcel_history->save()) {
                            $transactionManager->commit();
                            return true;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Util::slackDebug("EXCEPTION LOG", $e->getMessage());
        }

        $transactionManager->rollback();
        return false;
    }

    public static function getByWaybillNumber($waybill_number)
    {
        return Parcel::findFirst([
            'waybill_number = :waybill_number:',
            'bind' => ['waybill_number' => trim(strtoupper($waybill_number))]
        ]);
    }

    public static function getByWaybillNumberList($waybill_number_arr, $make_assoc = false)
    {
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Parcel')
            ->inWhere('waybill_number', $waybill_number_arr);
        $data = $builder->getQuery()->execute();

        if ($make_assoc) {
            $assoc_data = [];
            /**
             * @var Parcel $parcel
             */
            foreach ($data as $parcel) {
                $assoc_data[$parcel->getWaybillNumber()] = $parcel;
            }
            return $assoc_data;
        }
        return $data;
    }

    public function createSub(&$transaction)
    {
        $check = true;
        $waybill_number_arr = [];

        for ($i = 1; $i <= $this->getNoOfPackage(); ++$i) {
            $waybill_number = $this->getWaybillNumber() . '-' . $i . '-' . $this->getNoOfPackage();
            $sub_parcel = new Parcel();
            $sub_parcel->setTransaction($transaction);
            $sub_parcel->initData(
                $this->getParcelType(),
                $this->getSenderId(),
                $this->getSenderAddressId(),
                $this->getReceiverId(),
                $this->getReceiverAddressId(),
                null,
                null,
                $this->getCashOnDelivery(),
                null,
                $this->getDeliveryType(),
                $this->getPaymentType(),
                $this->getShippingType(),
                $this->getFromBranchId(),
                $this->getToBranchId(),
                $this->getStatus(),
                null,
                null,
                "N/A",
                null,
                null,
                null,
                $this->getCreatedBy(),
                1,
                Parcel::ENTITY_TYPE_SUB,
                $waybill_number,
                $this->getBankAccountId(),
                $this->getIsBillingOverridden(),
                $this->getReferenceNumber(),
                $this->getRouteId(),
                $this->getRequestType(),
                $this->getBillingType(),
                $this->getWeightBillingPlanId(),
                $this->getOnforwardingBillingPlanId(),
                $this->getIsFreightIncluded()
            );

            if (!$sub_parcel->save()) {
                $check = false;
                break;
            }
            $linked_parcel = new LinkedParcel();
            $linked_parcel->setTransaction($transaction);
            $linked_parcel->initData($this->getId(), $sub_parcel->getId());
            if (!$linked_parcel->save()) {
                $check = false;
                break;
            }

            $waybill_number_arr[] = $waybill_number;
        }

        if ($check) {
            return $waybill_number_arr;
        }

        return false;
    }

    public static function bagParcels($from_branch_id, $to_branch_id, $created_by, $status, $waybill_number_arr, $seal_id, $disable_branch_check = false)
    {
        $bag = new Parcel();
        $bag->initDataWithBasicInfo(
            $from_branch_id,
            $to_branch_id,
            $created_by,
            $status,
            uniqid(),
            Parcel::ENTITY_TYPE_BAG,
            0
        );
        $bag->seal_id = $seal_id;
        $bad_parcels = [];
        if ($bag->save()) {
            $bag->generateWaybillNumber($from_branch_id);
            if ($bag->save()) {
                $obj = new Parcel();
                $builder = $obj->getModelsManager()->createBuilder()
                    ->from('Parcel')
                    ->inWhere('waybill_number', $waybill_number_arr);

                $data = $builder->getQuery()->execute();

                /**
                 * @var Parcel $item
                 */
                foreach ($data as $item) {
                    if (!in_array($item->getEntityType(), [Parcel::ENTITY_TYPE_NORMAL, Parcel::ENTITY_TYPE_SUB])) {
                        $bad_parcels[$item->getWaybillNumber()] = ResponseMessage::PARCEL_NOT_BE_BAGGED;
                        continue;
                    }

                    if ($item->getIsVisible() == 0) {
                        $bad_parcels[$item->getWaybillNumber()] = ResponseMessage::PARCEL_ALREADY_BAGGED;
                        continue;
                    }

                    if ($item->getFromBranchId() != $from_branch_id && !$disable_branch_check) {
                        $bad_parcels[$item->getWaybillNumber()] = ResponseMessage::PARCEL_NOT_IN_OFFICER_BRANCH;
                        continue;
                    }


                    $item->setIsVisible(0);
                    $item->setFromBranchId($from_branch_id);
                    $item->setToBranchId($to_branch_id);
                    $item->setStatus($status);
                    $item->setModifiedDate(date('Y-m-d H:i:s'));
                    if (!$item->save()) {
                        $bad_parcels[$item->getWaybillNumber()] = ResponseMessage::INTERNAL_ERROR;
                        continue;
                    }
                    $linked_parcel = new LinkedParcel();
                    $linked_parcel->initData($bag->getId(), $item->getId());
                    if (!$linked_parcel->save()) {
                        $bad_parcels[$item->getWaybillNumber()] = ResponseMessage::INTERNAL_ERROR;
                        continue;
                    }
                }

                $response['bag_number'] = null;
                $no_of_parcels_in_bag = count($waybill_number_arr) - count($bad_parcels);
                if ($no_of_parcels_in_bag > 0) {
                    $bag->setIsVisible(1);
                    $bag->setNoOfPackage($no_of_parcels_in_bag);

                    if ($bag->save()) {
                        $response['bag_number'] = $bag->getWaybillNumber();
                    }
                } else {
                    $bag->delete();
                }

                $response['bad_parcels'] = $bad_parcels;
                return $response;
            }
        }
        return false;
    }

    public static function unbagParcels($bag_waybill_number)
    {
        $bag = Parcel::findFirst([
            'waybill_number = :waybill_number: AND entity_type = :entity_type:',
            'bind' => ['waybill_number' => $bag_waybill_number, 'entity_type' => Parcel::ENTITY_TYPE_BAG]
        ]);

        if ($bag == false) {
            return false;
        }
        $manager = new self();
        $connection = $manager->getWriteConnection();
        $sql = Parcel::SQL_MAKE_SUB_VISIBLE;

        $check = $connection->execute($sql, ['parent_id' => $bag->getId(), 'modified_date' => date('Y-m-d H:i:s')]);
        if ($check) {
            $bag->setIsVisible(0);
            $bag->setModifiedDate(date('Y-m-d H:i:s'));
            if ($bag->save()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Remove one or more parcels from a bag
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param int $bag_id - The waybill number of a bag
     * @param int[] $parcel_id_arr - An array of parcels id
     * @return bool
     */
    public static function removeFromBag($bag_id, $parcel_id_arr)
    {
        if (empty($parcel_id_arr)) {
            return true;
        }

        $clean_arr = [];
        foreach ($parcel_id_arr as $id) {
            $clean_arr[] = intval($id);
        }

        $child_id_str = implode(',', $clean_arr);
        $sql_link_delete = str_replace(':child_id', $child_id_str, Parcel::SQL_DELETE_SOME_LINKAGE);
        $sql_sub_viaible = str_replace(':child_id', $child_id_str, Parcel::SQL_MAKE_SOME_SUB_VISIBLE);

        $manager = new self();
        $connection = $manager->getWriteConnection();
        $connection->begin();
        try {
            $check = $connection->execute($sql_sub_viaible, ['parent_id' => $bag_id, 'modified_date' => date('Y-m-d H:i:s')]);
            if ($check) {
                $check = $connection->execute($sql_link_delete, ['parent_id' => $bag_id]);
                if ($check) {
                    $connection->commit();
                    return true;
                }
            }
        } catch (Exception $e) {

        }
        $connection->rollback();
        return false;
    }

    public function alterSubs()
    {
        $connection = $this->getWriteConnection();
        $sql = Parcel::SQL_UPDATE_SUBS;

        return $connection->execute($sql, [
            'parent_id' => $this->getId(),
            'from_branch_id' => $this->getFromBranchId(),
            'to_branch_id' => $this->getToBranchId(),
            'status' => $this->getStatus(),
            'modified_date' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_numbers
     * @return array
     */
    public static function unsortParcels($waybill_numbers)
    {
        $successful = [];
        $failed = [];
        foreach ($waybill_numbers as $waybill_number) {
            try {
                self::unsortParcel($waybill_number);
                $successful[] = $waybill_number;
            } catch (Exception $ex) {
                $failed[$waybill_number] = $ex->getMessage();
            }
        }
        return ['successful' => $successful, 'failed' => $failed];
    }


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @return bool
     * @throws Exception
     */
    public static function unsortParcel($waybill_number)
    {
        if (!is_string($waybill_number) || !(self::isWaybillNumber($waybill_number) || self::isBagNumber($waybill_number))) {
            throw new Exception('Invalid waybill number');
        }

        if (self::isBagNumber($waybill_number)) {
            throw new Exception('Cannot unsort a bag');
        }


        $parcel = Parcel::findFirst(['conditions' => 'waybill_number =:waybill_number:', 'bind' => ['waybill_number' => $waybill_number]]);
        if (!$parcel) {
            throw new Exception('Could not find parcel with waybill_number ' . $waybill_number);
        }

        /** @var Resultset $parcelSortHistory */
        $parcelSortHistory = ParcelHistory::find(['conditions' => 'parcel_id = :parcel_id:', 'bind' => ['parcel_id' => $parcel->getId()], 'limit' => 2, 'order' => 'id DESC']);
        if ($parcelSortHistory->count() < 2) {
            throw new Exception('Parcel ' . $waybill_number . ' does not have a proper sort history. Please confirm that parcel has been sorted');
        }

        if (!in_array($parcelSortHistory->getFirst()->status->id, [Status::PARCEL_FOR_SWEEPER, Status::PARCEL_FOR_GROUNDSMAN, Status::PARCEL_FOR_DELIVERY])) {

        }

        $previousParcelState = $parcelSortHistory->getLast();
        $parcel->from_branch_id = $previousParcelState->from_branch_id;
        $parcel->to_branch_id = $previousParcelState->to_branch_id;
        $parcel->setStatus($previousParcelState->status->id);

        if (!$parcel->save()) {
            throw new Exception('Could not unsort parcel');
        }

        //rewrite history
        $parcelSortHistory->getFirst()->delete();

        return true;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @return int
     */
    public static function isWaybillNumber($waybill_number)
    {
        return preg_match('/^\d[A-Z](\d|\-)+[\d]$/i', $waybill_number);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $bag_number
     * @return int
     */
    public static function isBagNumber($bag_number)
    {
        return preg_match('/^[B][\d]{11,}$/i', $bag_number);
    }

    /**
     * Get proof of delivery - receiver detail or signature delivery receipt
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function getProofOfDelivery()
    {
        $delivery_receipt = $this->getDeliveryReceipts("receipt_type = '" . DeliveryReceipt::RECEIPT_TYPE_SIGNATURE .
            "' OR receipt_type = '" . DeliveryReceipt::RECEIPT_TYPE_RECEIVER_DETAIL . "'");
        $delivery_receipt = ($delivery_receipt->getFirst()) ? $delivery_receipt->getFirst()->toArray() : false;
        if ($delivery_receipt && $delivery_receipt['receipt_type'] == DeliveryReceipt::RECEIPT_TYPE_SIGNATURE) {
            $delivery_receipt['receipt_path'] = DeliveryReceipt::getS3BaseUrl() . $delivery_receipt['receipt_path'];
        }
        return $delivery_receipt;
    }

    /**
     * Move Parcels to for sweeper
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number_arr
     * @param $to_branch_id
     * @return array
     */
    public static function bulkMoveToForSweeper($waybill_number_arr, $to_branch_id)
    {
        $bad_parcels = [];
        $good_parcels = [];
        /** @var Auth $auth */
        $auth = Di::getDefault()->getAuth();
        foreach ($waybill_number_arr as $waybill_number) {
            try {
                self::moveToSweeper($waybill_number, $auth, $to_branch_id);
                $good_parcels[] = $waybill_number;
            } catch (Exception $ex) {
                $bad_parcels[$waybill_number] = $ex->getMessage();
            }
        }
        return ['bad_parcels' => $bad_parcels, 'good_parcels' => $good_parcels];
    }

    /**
     * Move a parcel to for sweeper status
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @param Auth $auth
     * @param $to_branch_id
     * @return bool
     * @throws Exception
     */
    public static function moveToSweeper($waybill_number, $auth, $to_branch_id)
    {
        $parcel = self::getByWaybillNumber($waybill_number);

        if ($parcel === false) {
            throw new Exception(ResponseMessage::PARCEL_NOT_EXISTING);
        }

        if ($parcel->getStatus() == Status::PARCEL_FOR_SWEEPER) {
            throw new Exception(ResponseMessage::PARCEL_ALREADY_FOR_SWEEPER);
        } else if (!in_array($parcel->getStatus(), [Status::PARCEL_ARRIVAL, Status::PARCEL_FOR_GROUNDSMAN])) {
            throw new Exception(ResponseMessage::PARCEL_NOT_FROM_ARRIVAL);
        } else if ($parcel->getToBranchId() != $auth->getData()['branch']['id']) {
            throw new Exception(ResponseMessage::PARCEL_NOT_IN_OFFICE);
        }

        $check = $parcel->changeDestination(Status::PARCEL_FOR_SWEEPER, $to_branch_id, $auth->getPersonId(), ParcelHistory::MSG_FOR_SWEEPER);
        if (!$check) {
            throw new Exception(ResponseMessage::CANNOT_MOVE_PARCEL);
        } else {
            return true;
        }
    }


    /**
     * Bulk assign to groundsman
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number_arr
     * @return array
     */
    public static function bulkAssignToGroundsman($waybill_number_arr)
    {
        $auth = Di::getDefault()->getAuth();
        $bad_parcel = [];
        $good_parcels = [];

        foreach ($waybill_number_arr as $waybill_number) {
            try {
                self::assignToGroundsman($waybill_number, $auth);
                $good_parcels[] = $waybill_number;
            } catch (Exception $ex) {
                $bad_parcels[$waybill_number] = $ex->getMessage();
            }
        }
        return ['bad_parcels' => $bad_parcel, 'good_parcels' => $good_parcels];
    }

    /**
     * Assign a parcel to grounds man
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @param Auth $auth
     * @return bool
     * @throws Exception
     */
    public static function assignToGroundsman($waybill_number, $auth)
    {
        $parcel = Parcel::getByWaybillNumber($waybill_number);
        if ($parcel === false) {
            throw new Exception(ResponseMessage::PARCEL_NOT_EXISTING);
        }

        if ($parcel->getStatus() != Status::PARCEL_ARRIVAL) {
            throw new Exception(ResponseMessage::PARCEL_NOT_FROM_ARRIVAL);

        } else if ($parcel->getToBranchId() != $auth->getData()['branch_id']) {
            throw new Exception(ResponseMessage::PARCEL_NOT_IN_OFFICE);
        }
        $check = $parcel->changeDestination(Status::PARCEL_FOR_GROUNDSMAN, $auth->getData()['branch_id'], $auth->getPersonId(), ParcelHistory::MSG_ASSIGNED_TO_GROUNDSMAN);
        if (!$check) {
            throw new Exception(ResponseMessage::CANNOT_MOVE_PARCEL);
        } else {
            return true;
        }
    }

    /**
     * Get waybill array from comma seperated list of waybill numbers
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_numbers
     * @return array
     */
    public static function sanitizeWaybillNumbers($waybill_numbers)
    {
        $waybill_number_arr = explode(',', $waybill_numbers);

        $clean_arr = [];
        foreach ($waybill_number_arr as $number) {
            $clean_arr[trim(strtoupper($number))] = true;
        }

        return array_keys($clean_arr);
    }


}
