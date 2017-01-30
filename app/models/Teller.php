<?php
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Teller extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $bank_id;

    /**
     *
     * @var integer
     */
    protected $account_no;

    /**
     *
     * @var string
     */
    protected $account_name;

    /**
     *
     * @var string
     */
    protected $teller_no;

    /**
     *
     * @var double
     */
    protected $amount_paid;

    /**
     *
     * @var string
     */
    protected $snapshot;

    /**
     *
     * @var integer
     */
    protected $branch_id;

    /**
     *
     * @var integer
     */
    protected $paid_by;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $created_by;

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
     * @var integer
     */
    protected $approved_by;

    /**
     * @var string
     */
    protected $approval_date;


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
     * Method to set the value of field bank_id
     *
     * @param integer $bank_id
     * @return $this
     */
    public function setBankId($bank_id)
    {
        $this->bank_id = $bank_id;

        return $this;
    }

    /**
     * Method to set the value of field account_no
     *
     * @param integer $account_no
     * @return $this
     */
    public function setAccountNo($account_no)
    {
        $this->account_no = $account_no;

        return $this;
    }

    /**
     * Method to set the value of field account_name
     *
     * @param string $account_name
     * @return $this
     */
    public function setAccountName($account_name)
    {
        $this->account_name = $account_name;

        return $this;
    }

    /**
     * Method to set the value of field teller_no
     *
     * @param string $teller_no
     * @return $this
     */
    public function setTellerNo($teller_no)
    {
        $this->teller_no = $teller_no;

        return $this;
    }

    /**
     * Method to set the value of field amount_paid
     *
     * @param double $amount_paid
     * @return $this
     */
    public function setAmountPaid($amount_paid)
    {
        $this->amount_paid = $amount_paid;

        return $this;
    }

    /**
     * Method to set the value of field snapshot
     *
     * @param string $snapshot
     * @return $this
     */
    public function setSnapshot($snapshot)
    {
        $this->snapshot = $snapshot;

        return $this;
    }

    /**
     * Method to set the value of field branch_id
     *
     * @param integer $branch_id
     * @return $this
     */
    public function setBranchId($branch_id)
    {
        $this->branch_id = $branch_id;

        return $this;
    }

    /**
     * Method to set the value of field paid_by
     *
     * @param integer $paid_by
     * @return $this
     */
    public function setPaidBy($paid_by)
    {
        $this->paid_by = $paid_by;

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
     * @param string $approval_date
     * @return $this
     */
    public function setApprovalDate($approval_date)
    {
        $this->approval_date = $approval_date;
    }

    /**
     * @param int $approved_by
     * @return $this
     */
    public function setApprovedBy($approved_by)
    {
        $this->approved_by = $approved_by;
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
     * Returns the value of field bank_id
     *
     * @return integer
     */
    public function getBankId()
    {
        return $this->bank_id;
    }

    /**
     * Returns the value of field account_no
     *
     * @return integer
     */
    public function getAccountNo()
    {
        return $this->account_no;
    }

    /**
     * Returns the value of field account_name
     *
     * @return string
     */
    public function getAccountName()
    {
        return $this->account_name;
    }

    /**
     * Returns the value of field teller_no
     *
     * @return string
     */
    public function getTellerNo()
    {
        return $this->teller_no;
    }

    /**
     * Returns the value of field amount_paid
     *
     * @return double
     */
    public function getAmountPaid()
    {
        return $this->amount_paid;
    }

    /**
     * Returns the value of field snapshot
     *
     * @return string
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getBranchId()
    {
        return $this->branch_id;
    }

    /**
     * Returns the value of field paid_by
     *
     * @return integer
     */
    public function getPaidBy()
    {
        return $this->paid_by;
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
     * Returns the value of field created_by
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
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
     * @return string
     */
    public function getApprovalDate()
    {
        return $this->approval_date;
    }

    /**
     * @return int
     */
    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('paid_by', 'Admin', 'id', array('alias' => 'Admin'));
        $this->belongsTo('bank_id', 'Bank', 'id', array('alias' => 'Bank'));
        $this->belongsTo('branch_id', 'Branch', 'id', array('alias' => 'Branch'));
        $this->belongsTo('created_by', 'Admin', 'id', array('alias' => 'Admin'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Teller[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Teller
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
            'bank_id' => 'bank_id',
            'account_no' => 'account_no',
            'account_name' => 'account_name',
            'teller_no' => 'teller_no',
            'amount_paid' => 'amount_paid',
            'snapshot' => 'snapshot',
            'branch_id' => 'branch_id',
            'paid_by' => 'paid_by',
            'status' => 'status',
            'created_by' => 'created_by',
            'created_date' => 'created_date',
            'modified_date' => 'modified_date',
            'approval_date' => 'approval_date',
            'approved_by' => 'approved_by'
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'teller';
    }

    public static function fetchAll($offset, $count, $filter_by, $fetch_with, $order_by_clause = null)
    {
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from('Teller');




        if (!isset($filter_by['send_all'])) {
            $builder->limit($count, $offset);
        }

        $columns = ['Teller.*'];

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        //if the current user is a regional manger, get teller for his region alone
        /**
         * @var Auth $auth
         */
        $auth = \Phalcon\Di::getDefault()->getAuth();
        if($auth->getUserType() == Role::REGIONAL_MANAGER){

            $region = Region::findFirst([
                'conditions' => 'Region.manager_id = :staff_id:',
                'bind' => ['staff_id'  => $auth->getPersonId()]
            ]);

            if(!$region) return [];

            $builder->innerJoin('Admin', 'Teller.created_by = Admin.id');
            $builder->innerJoin('Branch', 'Branch.id = Admin.branch_id');
            $builder->innerJoin('State', 'State.id = Branch.state_id');
            $builder->innerJoin('Region', 'Region.id = State.region_id');

            $where[] = 'Region.id = :id:';
            $bind['id'] = $region->getId();

        }

        if ($order_by_clause != null) {
            $builder->orderBy($order_by_clause);
        } else if (isset($filter_by['start_modified_date']) or isset($filter_by['end_modified_date'])) {
            $builder->orderBy('Teller.modified_date');
        } else {
            $builder->orderBy('Teller.id');
        }

        //model hydration
        if (isset($fetch_with['with_bank'])) {
            $columns[] = 'Bank.*';
            $builder->leftJoin('Bank', 'Bank.id = Teller.bank_id');
        }
        if (isset($fetch_with['with_branch'])) {
            $columns[] = 'Branch.*';
            $builder->leftJoin('Branch', 'Branch.id = Teller.branch_id');
        }
        if (isset($fetch_with['with_payer'])) {
            $columns[] = 'Admin.*';
            $builder->leftJoin('Admin', 'Admin.id = Teller.paid_by');
        }

        $builder->where(join(' AND ', $where));

        if (isset($filter_by['teller_no'])) {
            $teller_no = explode(',', $filter_by['teller_no']);

            $builder->inWhere('Teller.teller_no', $teller_no);
        }

        $builder->columns($columns);
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            $teller = [];
            if ($item->teller == null) {
                $teller = $item->getData();
            } else {
                $teller = $item->teller->getData();
                if (isset($fetch_with['with_bank'])) {
                    $teller['bank'] = $item->bank->getData();
                }
                if (isset($fetch_with['with_branch'])) {
                    $teller['branch'] = $item->branch->getData();
                }
                if (isset($fetch_with['with_payer'])) {
                    $teller['payer'] = $item->admin->getData();
                }
            }
            $result[] = $teller;
        }
        return $result;
    }

    public static function fetchOne($id, $in_recursion = false)
    {
        $obj = new Parcel();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns(['Teller.*', 'Parcel.*'])
            ->from('Teller')
            ->leftJoin('TellerParcel', 'TellerParcel.teller_id = Teller.id', 'TellerParcel')
            ->leftJoin('Parcel', 'Parcel.id = TellerParcel.parcel_id', 'Parcel')
            ->where('Teller.id = :id:');

        $data = $builder->getQuery()->execute(['id' => $id]);
        if (count($data) == 0) return false;

        $result = $data[0]->teller->getData();
        $result['parcel'] = $data[0]->parcel->getData();

        return $result;
    }

    private static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];

        //filters
        if (isset($filter_by['bank_id'])) {
            $where[] = 'Teller.bank_id = :bank_id:';
            $bind['bank_id'] = $filter_by['bank_id'];
        }
        if (isset($filter_by['created_by'])) {
            $where[] = 'Teller.created_by = :created_by:';
            $bind['created_by'] = $filter_by['created_by'];
        }

        if(isset($filter_by['branch_id'])){
            $where[]= 'Teller.branch_id = :branch_id:';
            $bind['branch_id'] = $filter_by['branch_id'];
        }

        if (isset($filter_by['min_amount_paid'])) {
            $where[] = 'Teller.amount_paid >= :min_amount_paid:';
            $bind['min_amount_paid'] = $filter_by['min_amount_paid'];
        }
        if (isset($filter_by['max_amount_paid'])) {
            $where[] = 'Teller.amount_paid <= :max_amount_paid:';
            $bind['max_amount_paid'] = $filter_by['max_amount_paid'];
        }
        if (isset($filter_by['start_created_date'])) {
            $where[] = 'Teller.created_date >= :start_created_date:';
            $bind['start_created_date'] = $filter_by['start_created_date'];
        }
        if (isset($filter_by['end_created_date'])) {
            $where[] = 'Teller.created_date <= :end_created_date:';
            $bind['end_created_date'] = $filter_by['end_created_date'];
        }
        if (isset($filter_by['start_modified_date'])) {
            $where[] = 'Teller.modified_date >= :start_modified_date:';
            $bind['start_modified_date'] = $filter_by['start_modified_date'];
        }
        if (isset($filter_by['end_modified_date'])) {
            $where[] = 'Teller.modified_date <= :end_modified_date:';
            $bind['end_modified_date'] = $filter_by['end_modified_date'];
        }
        if (isset($filter_by['teller_no'])) {
            $where[] = 'Teller.teller_no LIKE :teller_no:';
            $bind['teller_no'] = '%' . $filter_by['teller_no'] . '%';
        }
        if (isset($filter_by['status'])) {
            $where[] = 'Teller.status = :status:';
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
    public static function tellerCount($filter_by)
    {
        $obj = new Teller();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS teller_count')
            ->from('Teller');

        //filters
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->teller_count);
    }

    /**
     * Initializes the details of the teller
     *
     * @param bank_id
     * @param account_name
     * @param account_no
     * @param teller_no
     * @param amount_paid
     * @param paid_by
     * @param created_by
     * @param branch_id
     * @param status
     *
     * @author  Olawale Lawal
     */

    public function initData($bank_id, $account_name, $account_no, $teller_no, $amount_paid, $paid_by, $created_by, $branch_id, $status)
    {
        $this->setBankId($bank_id);
        $this->setAccountName($account_name);
        $this->setAccountNo($account_no);
        $this->setTellerNo($teller_no);
        $this->setAmountPaid($amount_paid);
        $this->setPaidBy($paid_by);
        $this->setCreatedBy($created_by);
        $this->setBranchId($branch_id);

        $now = date('Y-m-d H:i:s');
        $this->setCreatedDate($now);
        $this->setModifiedDate($this->getCreatedDate());
        $this->setStatus($status);
    }

    /**
     * Saves the details of the teller
     *
     * @param bank_id
     * @param account_name
     * @param account_no
     * @param teller_no
     * @param amount_paid
     * @param parcel_id_array
     * @param paid_by
     * @param created_by
     * @param branch_id
     *
     * @author  Olawale Lawal
     * @return array
     */
    public function saveForm($bank_id, $account_name, $account_no, $teller_no, $amount_paid, $parcel_id_array, $paid_by, $created_by, $branch_id)
    {

        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $this->setTransaction($transaction);
            $this->initData($bank_id, $account_name, $account_no, $teller_no, $amount_paid, $paid_by, $created_by, $branch_id, Status::TELLER_AWAITING_APPROVAL);

            $check = $this->save();

            //saving the parcels with the teller id
            if ($check) {
                foreach ($parcel_id_array as $parcel_id) {
                    $teller_parcel = new TellerParcel();
                    $teller_parcel->setTransaction($transaction);
                    $teller_parcel->initData($this->getId(), $parcel_id);
                    $check = $teller_parcel->save();
                }
            } else {
                return false;
            }

            if ($check) {
                $transactionManager->commit();
                return $this->getId();
            }
        } catch (Exception $e) {
            /*if ($e->getPrevious()) {
                print $e->getPrevious()->getMessage() . "\n";
            } else {
                print $e->getMessage() . "\n";
            }*/
        }

        $transactionManager->rollback();
        return false;
    }

    /**
     * Returns the details of the teller
     *
     * @author  Olawale Lawal
     * @return array
     */
    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'teller_no' => $this->getTellerNo(),
            'branch_id' => $this->getBranchId(),
            'account_no' => $this->getAccountNo(),
            'bank_id' => $this->getBankId(),
            'amount_paid' => $this->getAmountPaid(),
            'status' => $this->getStatus(),
            'created_date' => $this->getCreatedDate(),
            'created_by' => $this->getCreatedBy(),
            'paid_by' => $this->getPaidBy(),
            'modified_date' => $this->getModifiedDate(),
            'approved_by' => $this->getApprovedBy(),
            'approval_date' => $this->getApprovalDate()
        );
    }

    /**
     * Searches for a teller
     *
     * @param bank_id
     * @param teller_no
     * @author  Olawale Lawal
     * @return Teller
     */
    public static function getTeller($bank_id, $teller_no)
    {
        return Teller::findFirst([
            'bank_id = :bank_id: AND teller_no = :teller_no:',
            'bind' => array('bank_id' => $bank_id, 'teller_no' => $teller_no)
        ]);
    }
}
