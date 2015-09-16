<?php
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Manifest extends \Phalcon\Mvc\Model
{
    const TYPE_SWEEP = 1;
    const TYPE_DELIVERY = 2;

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $type_id;

    /**
     *
     * @var string
     */
    protected $label;

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
    protected $sender_admin_id;

    /**
     *
     * @var integer
     */
    protected $receiver_admin_id;

    /**
     *
     * @var integer
     */
    protected $held_by_id;

    /**
     *
     * @var double
     */
    protected $weight;

    /**
     *
     * @var integer
     */
    protected $no_of_parcels;

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
     * Method to set the value of field type_id
     *
     * @param integer $type_id
     * @return $this
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;

        return $this;
    }

    /**
     * Method to set the value of field label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * Method to set the value of field sender_admin_id
     *
     * @param integer $sender_admin_id
     * @return $this
     */
    public function setSenderAdminId($sender_admin_id)
    {
        $this->sender_admin_id = $sender_admin_id;

        return $this;
    }

    /**
     * Method to set the value of field receiver_admin_id
     *
     * @param integer $receiver_admin_id
     * @return $this
     */
    public function setReceiverAdminId($receiver_admin_id)
    {
        $this->receiver_admin_id = $receiver_admin_id;

        return $this;
    }

    /**
     * Method to set the value of field held_by_id
     *
     * @param integer $held_by_id
     * @return $this
     */
    public function setHeldById($held_by_id)
    {
        $this->held_by_id = $held_by_id;

        return $this;
    }

    /**
     * Method to set the value of field weight
     *
     * @param double $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Method to set the value of field no_of_parcels
     *
     * @param integer $no_of_parcels
     * @return $this
     */
    public function setNoOfParcels($no_of_parcels)
    {
        $this->no_of_parcels = $no_of_parcels;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field type_id
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Returns the value of field label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
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
     * @return integer
     */
    public function getToBranchId()
    {
        return $this->to_branch_id;
    }

    /**
     * Returns the value of field sender_admin_id
     *
     * @return integer
     */
    public function getSenderAdminId()
    {
        return $this->sender_admin_id;
    }

    /**
     * Returns the value of field receiver_admin_id
     *
     * @return integer
     */
    public function getReceiverAdminId()
    {
        return $this->receiver_admin_id;
    }

    /**
     * Returns the value of field held_by_id
     *
     * @return integer
     */
    public function getHeldById()
    {
        return $this->held_by_id;
    }

    /**
     * Returns the value of field weight
     *
     * @return double
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Returns the value of field no_of_parcels
     *
     * @return integer
     */
    public function getNoOfParcels()
    {
        return $this->no_of_parcels;
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
     * Returns the value of field status
     *
     * @return integer
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
        $this->hasMany('id', 'Held_parcel', 'manifest_id', array('alias' => 'Held_parcel'));
        $this->belongsTo('from_branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('to_branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('sender_admin_id', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('receiver_admin_id', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('held_by_id', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('status', 'Status', 'id', array('alias' => 'Status'));
    }

    /**
     * @return Manifest[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Manifest
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
            'type_id' => 'type_id',
            'label' => 'label',
            'from_branch_id' => 'from_branch_id',
            'to_branch_id' => 'to_branch_id',
            'sender_admin_id' => 'sender_admin_id',
            'receiver_admin_id' => 'receiver_admin_id',
            'held_by_id' => 'held_by_id',
            'weight' => 'weight',
            'no_of_parcels' => 'no_of_parcels',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'status' => 'status'
        );
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'type_id' => $this->getTypeId(),
            'label' => $this->getLabel(),
            'from_branch_id' => $this->getFromBranchId(),
            'to_branch_id' => $this->getToBranchId(),
            'sender_admin_id' => $this->getSenderAdminId(),
            'receiver_admin_id' => $this->getReceiverAdminId(),
            'held_by_id' => $this->getHeldById(),
            'weight' => $this->getWeight(),
            'no_of_parcels' => $this->getNoOfParcels(),
            'created_date' => $this->getCreatedDate(),
            'modified_date' => $this->getModifiedDate(),
            'status' => $this->getStatus()
        );
    }

    public function initData($label, $from_branch_id, $to_branch_id, $sender_admin_id, $held_by_id, $type_id)
    {
        $this->setTypeId($type_id);
        $this->setLabel($label);
        $this->setFromBranchId($from_branch_id);
        $this->setToBranchId($to_branch_id);
        $this->setSenderAdminId($sender_admin_id);
        $this->setReceiverAdminId(null);
        $this->setHeldById($held_by_id);
        $this->setWeight(0);
        $this->setNoOfParcels(0);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($now);
        $this->setStatus(Status::MANIFEST_IN_TRANSIT);
    }

    protected function setMetrics($weight, $no_of_parcels)
    {
        $this->setWeight($weight);
        $this->setNoOfParcels($no_of_parcels);
    }

    public function changeStatus($status)
    {
        $this->setStatus($status);
        $this->setModifiedDate(date('Y-m-d H:i:s'));
    }

    public function recieve($receiver_admin_id, $status)
    {
        $this->changeStatus($status);
        $this->setReceiverAdminId($receiver_admin_id);
    }

    public static function getById($id)
    {
        return Manifest::findFirst([
            'id = :id:',
            'bind' => ['id' => $id]
        ]);
    }

    public static function createOne($parcel_arr, $label, $from_branch_id, $to_branch_id, $sender_admin_id, $held_by_id, $type_id)
    {
        if (empty($parcel_arr)) {
            return ['manifest' => null, 'bad_parcels' => []];
        }

        $bad_parcel = [];
        $manifest = new Manifest();
        $manifest->initData($label, $from_branch_id, $to_branch_id, $sender_admin_id, $held_by_id, $type_id);

        if ($manifest->save()) {
            $total_weight = 0; // todo: calculate this when bags and split parcels have weight
            $no_of_parcels = 0;
            /**
             * @var Parcel $parcel
             */
            $status = null;
            $history_comment = '';
            switch ($type_id) {
                case Manifest::TYPE_SWEEP:
                    $status = Status::PARCEL_IN_TRANSIT;
                    $history_comment = ParcelHistory::MSG_IN_TRANSIT;
                    break;
                case Manifest::TYPE_DELIVERY:
                    $status = Status::PARCEL_BEING_DELIVERED;
                    $history_comment = ParcelHistory::MSG_BEING_DELIVERED;
                    break;
                default:
                    break;
            }

            if ($status == null) {
                return false;
            }

            foreach ($parcel_arr as $parcel) {
                if ($parcel->getToBranchId() != $to_branch_id || $parcel->getFromBranchId() != $from_branch_id) {
                    $bad_parcel[$parcel->getId()] = ResponseMessage::MANIFEST_PARCEL_DIRECTION_MISMATCH;
                    continue;
                }
                $check = $parcel->checkout($status, $held_by_id, $sender_admin_id, $history_comment, $manifest->getId());
                if (!$check) {
                    $bad_parcel[$parcel->getId()] = ResponseMessage::CANNOT_MOVE_PARCEL;
                    continue;
                }
                $no_of_parcels++;
            }
            if ($no_of_parcels == 0) {
                $manifest->setId(null);
                $manifest->delete();
            } else {
                $manifest->setMetrics($total_weight, $no_of_parcels);
                $manifest->save();
            }
            return ['manifest' => $manifest, 'bad_parcels' => $bad_parcel];
        }

        return false;
    }

    public static function fetchOne($manifest_id, $fetch_with)
    {
        $obj = new Manifest();
        $builder = $obj->getModelsManager()->createBuilder()
            ->where('Manifest.id = :id:', ['id' => $manifest_id])
            ->from('Manifest');

        $columns = ['Manifest.*'];

        if (isset($fetch_with['with_from_branch'])) {
            $columns[] = 'FromBranch.*';
            $builder->innerJoin('FromBranch', 'FromBranch.id = Manifest.from_branch_id', 'FromBranch');
        }

        if (isset($fetch_with['with_to_branch'])) {
            $columns[] = 'ToBranch.*';
            $builder->innerJoin('ToBranch', 'ToBranch.id = Manifest.to_branch_id', 'ToBranch');
        }

        if (isset($fetch_with['with_sender_admin'])) {
            $columns[] = 'SenderAdmin.*';
            $builder->innerJoin('SenderAdmin', 'SenderAdmin.id = Manifest.sender_admin_id', 'SenderAdmin');
        }

        if (isset($fetch_with['with_receiver_admin'])) {
            $columns[] = 'ReceiverAdmin.*';
            $builder->leftJoin('ReceiverAdmin', 'ReceiverAdmin.id = Manifest.receiver_admin_id', 'ReceiverAdmin');
        }

        if (isset($fetch_with['with_holder'])) {
            $columns[] = 'Holder.*';
            $builder->innerJoin('Holder', 'Holder.id = Manifest.held_by_id', 'Holder');
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute();

        if (count($data) == 0) {
            return false;
        }

        $manifest = [];
        if (!isset($data[0]->manifest)) {
            $manifest = $data[0]->getData();
        } else {
            $manifest = $data[0]->manifest->getData();
            if (isset($fetch_with['with_from_branch'])) {
                $manifest['from_branch'] = $data[0]->fromBranch->getData();
            }
            if (isset($fetch_with['with_to_branch'])) {
                $manifest['to_branch'] = $data[0]->toBranch->getData();
            }
            if (isset($fetch_with['with_sender_admin'])) {
                $manifest['sender_admin'] = $data[0]->senderAdmin->getData();
            }
            if (isset($fetch_with['with_receiver_admin'])) {
                $manifest['receiver_admin'] = $data[0]->receiverAdmin->getData();
            }
            if (isset($fetch_with['with_holder'])) {
                $manifest['holder'] = $data[0]->holder->getData();
            }
        }

        return $manifest;
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with)
    {
        $obj = new Manifest();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Manifest')
            ->limit($count, $offset);

        $where = [];
        $bind = [];
        $columns = ['Manifest.*'];

        if (isset($filter_by['type_id'])) {
            $where[] = 'Manifest.type_id = :type_id:';
            $bind['type_id'] = $filter_by['type_id'];
        }
        if (isset($filter_by['from_branch_id'])) {
            $where[] = 'Manifest.from_branch_id = :from_branch_id:';
            $bind['from_branch_id'] = $filter_by['from_branch_id'];
        }
        if (isset($filter_by['to_branch_id'])) {
            $where[] = 'Manifest.to_branch_id = :to_branch_id:';
            $bind['to_branch_id'] = $filter_by['to_branch_id'];
        }
        if (isset($filter_by['sender_admin_id'])) {
            $where[] = 'Manifest.sender_admin_id = :sender_admin_id:';
            $bind['sender_admin_id'] = $filter_by['sender_admin_id'];
        }
        if (isset($filter_by['receiver_admin_id'])) {
            $where[] = 'Manifest.receiver_admin_id = :receiver_admin_id:';
            $bind['receiver_admin_id'] = $filter_by['receiver_admin_id'];
        }
        if (isset($filter_by['held_by_id'])) {
            $where[] = 'Manifest.held_by_id = :held_by_id:';
            $bind['held_by_id'] = $filter_by['held_by_id'];
        }
        if (isset($filter_by['status'])) {
            $where[] = 'Manifest.status = :status:';
            $bind['status'] = $filter_by['status'];
        }

        if (isset($filter_by['start_created_date'])) {
            $where[] = 'Manifest.created_date >= :start_created_date:';
            $bind['start_created_date'] = $filter_by['start_created_date'];
        }
        if (isset($filter_by['end_created_date'])) {
            $where[] = 'Manifest.created_date <= :end_created_date:';
            $bind['end_created_date'] = $filter_by['end_created_date'];
        }

        if (isset($fetch_with['with_from_branch'])) {
            $columns[] = 'FromBranch.*';
            $builder->innerJoin('FromBranch', 'FromBranch.id = Manifest.from_branch_id', 'FromBranch');
        }

        if (isset($fetch_with['with_to_branch'])) {
            $columns[] = 'ToBranch.*';
            $builder->innerJoin('ToBranch', 'ToBranch.id = Manifest.to_branch_id', 'ToBranch');
        }

        if (isset($fetch_with['with_sender_admin'])) {
            $columns[] = 'SenderAdmin.*';
            $builder->innerJoin('SenderAdmin', 'SenderAdmin.id = Manifest.sender_admin_id', 'SenderAdmin');
        }

        if (isset($fetch_with['with_receiver_admin'])) {
            $columns[] = 'ReceiverAdmin.*';
            $builder->leftJoin('ReceiverAdmin', 'ReceiverAdmin.id = Manifest.receiver_admin_id', 'ReceiverAdmin');
        }

        if (isset($fetch_with['with_holder'])) {
            $columns[] = 'Holder.*';
            $builder->innerJoin('Holder', 'Holder.id = Manifest.held_by_id', 'Holder');
        }

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            $manifest = [];
            if (!isset($item->manifest)) {
                $manifest = $item->getData();
            } else {
                $manifest = $item->manifest->getData();
                if (isset($fetch_with['with_from_branch'])) {
                    $manifest['from_branch'] = $item->fromBranch->getData();
                }
                if (isset($fetch_with['with_to_branch'])) {
                    $manifest['to_branch'] = $item->toBranch->getData();
                }
                if (isset($fetch_with['with_sender_admin'])) {
                    $manifest['sender_admin'] = $item->senderAdmin->getData();
                }
                if (isset($fetch_with['with_receiver_admin'])) {
                    $manifest['receiver_admin'] = $item->receiverAdmin->getData();
                }
                if (isset($fetch_with['with_holder'])) {
                    $manifest['holder'] = $item->holder->getData();
                }
            }
            $result[] = $manifest;
        }

        return $result;
    }
}
