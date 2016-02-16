<?php

class ParcelHistory extends \Phalcon\Mvc\Model
{
    const MSG_FOR_SWEEPER = 'Parcel ready for sweeping';
    const MSG_FOR_DELIVERY = 'Parcel ready for delivery';
    const MSG_FOR_ARRIVAL = 'Parcel is in arrival';
    const MSG_IN_TRANSIT = 'Parcel is in transit';
    const MSG_BEING_DELIVERED = 'Parcel ready for delivery';
    const MSG_DELIVERED = 'Parcel delivered';
    const MSG_CANCELLED = 'Parcel cancelled';
    const MSG_ASSIGNED_TO_GROUNDSMAN = 'Parcel assigned to the groundsman';
    const MSG_RETURNED = 'Parcel returned to the shipper';
    const MSG_RECEIVED_FROM_DISPATCHER = 'Parcel received from dispatcher';


    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $parcel_id;

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
    protected $admin_id;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $created_date;

    /**
     *
     * @var string
     */
    protected $description;

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
     * Method to set the value of field parcel_id
     *
     * @param integer $parcel_id
     * @return $this
     */
    public function setParcelId($parcel_id)
    {
        $this->parcel_id = $parcel_id;

        return $this;
    }

    /**
     * Method to set the value of field branch_id
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
     * Method to set the value of field branch_id
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
     * Method to set the value of field admin_id
     *
     * @param integer $admin_id
     * @return $this
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;

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
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Returns the value of field parcel_id
     *
     * @return integer
     */
    public function getParcelId()
    {
        return $this->parcel_id;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getFromBranchId()
    {
        return $this->from_branch_id;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getToBranchId()
    {
        return $this->to_branch_id;
    }

    /**
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
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
     * Returns the value of field created_date
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
        $this->belongsTo('parcel_id', 'Parcel', 'id', array('alias' => 'Parcel'));
        $this->belongsTo('branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('admin_id', 'Admin', 'id', array('alias' => 'Admin'));
    }

    /**
     * @return ParcelHistory[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return ParcelHistory
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
            'parcel_id' => 'parcel_id',
            'from_branch_id' => 'from_branch_id',
            'to_branch_id' => 'to_branch_id',
            'admin_id' => 'admin_id',
            'status' => 'status',
            'created_date' => 'created_date',
            'description' => 'description'
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'parcel_id' => $this->getParcelId(),
            'from_branch_id' => $this->getFromBranchId(),
            'to_branch_id' => $this->getToBranchId(),
            'admin_id' => $this->getAdminId(),
            'status' => $this->getStatus(),
            'created_date' => $this->getCreatedDate(),
            'description' => $this->getDescription()
        );
    }

    public function initData($parcel_id, $from_branch_id, $description, $admin_id, $status, $to_branch_id)
    {
        $this->setParcelId($parcel_id);
        $this->setFromBranchId($from_branch_id);
        $this->setToBranchId($to_branch_id);
        $this->setDescription($description);
        $this->setAdminId($admin_id);

        $this->setStatus($status);
        $this->setCreatedDate(date('Y-m-d H:i:s'));
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with)
    {
        $obj = new ParcelHistory();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('ParcelHistory');

        if (isset($filter_by['paginate'])) {
            $builder->limit($count, $offset);
        }

        $where = [];
        $bind = [];
        $columns = ['Parcel.*', 'ParcelHistory.*', 'FromBranch.*', 'ToBranch.*', 'ParcelComment.*'];

        if (isset($filter_by['waybill_number'])) {
            $waybill_number_array = explode(',', $filter_by['waybill_number']);
            $comma_separated = implode("','", $waybill_number_array);
            $comma_separated = "'$comma_separated'";
            $where[] = "Parcel.waybill_number IN ($comma_separated)  OR Parcel.reference_number IN ($comma_separated)";
        } else if (isset($filter_by['parcel_id'])) {
            $where[] = 'ParcelHistory.parcel_id = :parcel_id:';
            $bind['parcel_id'] = $filter_by['parcel_id'];
        }

        if (isset($filter_by['status'])) {
            $where[] = 'ParcelHistory.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        if (isset($fetch_with['with_admin'])) {
            $columns[] = 'Admin.*';
            $builder->innerJoin('Admin', 'Admin.id = ParcelHistory.admin_id');
        }

        //doing the left join after any possible inner join
        $builder->innerJoin('Parcel', 'Parcel.id = ParcelHistory.parcel_id');
        $builder->leftJoin('FromBranch', 'FromBranch.id = ParcelHistory.from_branch_id', 'FromBranch');
        $builder->leftJoin('ToBranch', 'ToBranch.id = ParcelHistory.to_branch_id', 'ToBranch');
        $builder->leftJoin('ParcelComment', 'Parcel.waybill_number = ParcelComment.waybill_number AND type = "' . ParcelComment::COMMENT_TYPE_RETURNED . '"');

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            if (!isset($result[$item->parcel->waybill_number])) {
                $result[$item->parcel->waybill_number]['parcel'] = $item->parcel->getData();
                $result[$item->parcel->waybill_number]['sender'] = User::findFirst($item->parcel->sender_id)->toArray();
                $result[$item->parcel->waybill_number]['receiver'] = User::findFirst($item->parcel->receiver_id)->toArray();
                if ($item->parcel->getStatus() == Status::PARCEL_DELIVERED) {
                    $result[$item->parcel->waybill_number]['delivery_receipt'] = $item->parcel->getProofOfDelivery();
                }
                $result[$item->parcel->waybill_number]['parcel_return_comment'] = $item->parcelComment->toArray();
            }
            $history = $item->parcelHistory->getData();
            $history['from_branch'] = (is_null($item->fromBranch->id)) ? null : $item->fromBranch->getData();
            $history['to_branch'] = (is_null($item->toBranch->id)) ? null : $item->toBranch->getData();
            if (isset($fetch_with['with_admin'])) {
                $history['sender_admin'] = $item->admin->getData();
            }
            $result[$item->parcel->waybill_number]['history'][] = $history;
        }
        return $result;
    }
}
