<?php
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;

/**
 * Class ParcelDraftSort
 * @property  string waybill_number
 * @property  int to_branch
 * @property  int created_by
 * @property string sort_number
 * @property int is_visible
 * @method Resultset getParcelDrafts
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ParcelDraftSort extends EagerModel
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $created_by
     * @param $count
     * @param $offset
     * @param bool $paginate
     * @return array
     */
    public static function getDraftSorts($created_by, $count, $offset, $paginate)
    {
        $obj = new ParcelDraftSort();
        $builder = $obj->getModelsManager()->createBuilder();
        $builder->from('ParcelDraftSort');

        if ($paginate) {
            $builder->limit($count, $offset);
        }

        $columns = ['ParcelDraftSort.*'];
        $filter_cond = self::getFilterConditions(['ParcelDraftSort.created_by' => $created_by]);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];
        $obj->setFetchWith(['with_to_branch', 'with_parcel', 'with_from_branch', 'with_receiver_address', 'with_receiver_address_city', 'with_receiver_address_state'])->joinWith($builder, $columns);

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            if (isset($item->parcelDraftSort)) {
                $parcelDraftSort = $item->parcelDraftSort->toArray();
                $relatedRecords = $obj->loadRelatedModels($item, true);
                $parcelDraftSort = array_merge($parcelDraftSort, $relatedRecords);
            } else {
                $parcelDraftSort = $item->toArray();
            }
            $result[] = $parcelDraftSort;
        }
        return $result;
    }

    /**
     *  Create draft parcel sorts
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_numbers
     * @param $to_branch
     * @param $created_by
     * @return array
     */
    public static function createDraftParcelSorts($waybill_numbers, $to_branch, $created_by)
    {
        $created_by = Admin::findFirst($created_by);
        $success = [];
        $failed = [];

        foreach ($waybill_numbers as $waybill_number) {
            try {
                if (self::createDraftParcelSort($waybill_number, $to_branch, $created_by)) {
                    $success[] = $waybill_number;
                } else {
                    $failed[$waybill_number] = 'An error occurred while saving draft sort';
                }
            } catch (Exception $ex) {
                $failed[$waybill_number] = $ex->getMessage();
            }
        }

        return ['successful' => $success, 'failed' => $failed];
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number string
     * @param $to_branch int
     * @param $created_by Admin
     * @return bool
     * @throws Exception
     */
    public static function createDraftParcelSort($waybill_number, $to_branch, $created_by)
    {
        $draftParcelSort = new ParcelDraftSort();
        if (!Parcel::isWaybillNumber($waybill_number)) {
            throw new Exception('Invalid waybill number');
        }

        $parcel = Parcel::getByWaybillNumber($waybill_number);

        if (!$parcel) {
            throw new Exception('Parcel does not exist');
        }

        if ($parcel->getToBranchId() != $created_by->getBranchId()) {
            throw new Exception('Parcel is not expected in this branch');
        }

        if ($parcel->getStatus() != Status::PARCEL_IN_TRANSIT) {
            throw new Exception('Parcel is not in transit');
        }

        $draftParcelSort->waybill_number = $parcel->getWaybillNumber();
        $draftParcelSort->to_branch = $to_branch;
        $draftParcelSort->created_by = $created_by->getId();
        $sort_number = 'DSP' . $created_by->getId() . $to_branch . $parcel->getId();
        $draftParcelSort->sort_number = $sort_number;
        return $draftParcelSort->save();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $sort_numbers
     * @param $to_branch
     * @param $created_by
     * @return bool
     * @throws Exception
     */
    public static function createDraftBag($sort_numbers, $to_branch, $created_by)
    {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $transaction->begin();
            $draftParcelSort = new ParcelDraftSort();
            $draftParcelSort->waybill_number = null;
            $draftParcelSort->to_branch = $to_branch;
            $draftParcelSort->created_by = $created_by->getId();
            $sort_number = 'DSB' . $created_by->getId() . $to_branch . date('u');
            $draftParcelSort->sort_number = $sort_number;
            if (!$draftParcelSort->save()) {
                $err = 'Could not save draft bag';
                $transaction->rollback($err);
                throw new Exception($err);
            }

            foreach ($sort_numbers as $sort_number) {
                /** @var self $draftSort */
                $draftSort = self::findFirstBySortNumber($sort_number);
                if (!$draftSort) {
                    continue;
                }
                $draftBagParcel = new DraftBagParcel();
                $draftBagParcel->bag_sort_number = $draftParcelSort->sort_number;
                $draftBagParcel->parcel_sort_number = $draftSort->sort_number;
                if (!$draftBagParcel->save()) {
                    $err = 'Could not save draft bag parcel '. $draftSort->sort_number;
                    $transaction->rollback($err);
                    throw new Exception($err);
                }
                $draftSort->is_visible = 0;
                if (!$draftSort->save()) {
                    $err = 'Could not set draft parcel ' . $draftSort->sort_number . ' to invisible';
                    $transaction->rollback($err);
                    throw new Exception($err);
                }
            }
            $transaction->commit();
            return true;

        } catch (TransactionFailed $tx) {
            if ($transaction->isValid()) {
                $transaction->rollback('Transaction failed');
            }
            return false;
        }
    }

    /**
     *  discard draft parcel sorts
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $sort_numbers
     * @return array
     */
    public static function discardSortings($sort_numbers)
    {
        $success = [];
        $failed = [];
        foreach ($sort_numbers as $sort_number) {
            try {
                if (self::discardSorting($sort_number)) {
                    $success[] = $sort_number;
                } else {
                    $failed[$sort_number] = 'An error occurred while deleting draft sort';
                }
            } catch (Exception $ex) {
                $failed[$sort_number] = $ex->getMessage();
            }
        }

        return ['successful' => $success, 'failed' => $failed];
    }

    /**
     * discard sorting
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $sort_number
     * @return bool
     * @throws Exception
     */
    public static function discardSorting($sort_number)
    {
        /** @var self $draftSortParcel */
        $draftSortParcel = self::findFirstBySortNumber($sort_number);

        if (!$draftSortParcel) {
            throw new Exception('Draft sorting does not exist');
        }

        //if draft sort is a bag
        if ($draftSortParcel->waybill_number == null) {
            $draftSortParcel->getParcelDrafts()->delete();
        }

        return $draftSortParcel->delete();
    }

    /**
     * Confirm sortings
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $sort_numbers
     * @return array
     */
    public static function confirmSortings($sort_numbers)
    {
        $auth = Di::getDefault()->getAuth();
        $success = [];
        $failed = [];
        foreach ($sort_numbers as $sort_number) {
            try {
                if (self::confirmSorting($sort_number, $auth)) {
                    $success[] = $sort_number;
                } else {
                    $failed[$sort_number] = 'An error occurred while confirming draft sort';
                }
            } catch (Exception $ex) {
                $failed[$sort_number] = $ex->getMessage();
            }
        }

        return ['successful' => $success, 'failed' => $failed];
    }

    /**
     * Confirm sorting
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $sort_number
     * @param Auth $auth
     * @return bool
     * @throws Exception
     */
    public static function confirmSorting($sort_number, $auth)
    {
        /** @var self $draftSortParcel */
        $draftSortParcel = self::findFirstBySortNumber($sort_number);

        if (!$draftSortParcel) {
            throw new Exception('Draft sorting does not exist');
        }

        if ($draftSortParcel->to_branch == $auth->getData()['branch_id']) {
            Parcel::assignToGroundsman($draftSortParcel->waybill_number, $auth);
        } else {
            Parcel::moveToSweeper($draftSortParcel->waybill_number, $auth, $draftSortParcel->to_branch);
        }

        $draftSortParcel->delete();

        return true;
    }

    /**
     * Confirm bag sort
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $sort_number
     * @param $seal_id
     * @param null $to_branch
     * @return bool
     * @throws Exception
     */
    public static function confirmBag($sort_number, $seal_id = null, $to_branch = null)
    {
        /** @var Auth $auth */
        $auth = Di::getDefault()->getAuth();

        /** @var self $draftBag */
        $draftBag = self::findFirst(['conditions' => 'sort_number=:sort_number AND waybill_number IS NULL', 'bind' => ['sort_number' => $sort_number]]);

        if (!$draftBag) {
            throw new Exception('Bag with sort number ' . $sort_number . ' does not exist');
        }


        $parcelDrafts = $draftBag->getParcelDrafts();
        $waybill_numbers = array_values($parcelDrafts->toArray(['waybill_number']));
        $sort_numbers = array_values($parcelDrafts->toArray('sort_number'));

        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $transaction->begin();
            $confirmSortingResult = self::confirmSortings($sort_numbers);

            if ($confirmSortingResult['failed']) {
                $err = 'Could not confirm draft parcels';
                $transaction->rollback($err);
                throw new Exception($err);
            }

            $to_branch = (is_null($to_branch)) ? $draftBag->to_branch : $to_branch;
            if (!Parcel::bagParcels($auth->getData()['branch_id'], $to_branch, $auth->getPersonId(), Status::ACTIVE, $waybill_numbers, $seal_id)) {
                $err = 'Could not confirm draft parcels';
                $transaction->rollback($err);
                throw new Exception($err);
            }

            if (!$draftBag->delete()) {
                $err = 'Could not confirm draft parcels';
                $transaction->rollback($err);
                throw new Exception($err);
            }

            $transaction->commit();
            return true;

        } catch (TransactionFailed $tx) {
            $err = $tx->getMessage();
            if ($transaction->isValid()) {
                $transaction->rollback($err);
            }
            throw new Exception($err);
        }

    }

    /**
     * @inheritdoc
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('parcel_draft_sorts');
        $this->hasMany('sort_number', DraftBagParcel::class, 'bag_sort_number', ['alias' => 'ParcelDrafts']);
    }

    /**
     * Returns an array that maps related models
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [
            [
                'field' => 'to_branch',
                'model_name' => 'ParcelDraftSort',
                'ref_model_name' => 'Branch',
                'foreign_key' => 'to_branch',
                'reference_key' => 'id'
            ],

            [
                'field' => 'parcel',
                'model_name' => 'ParcelDraftSort',
                'ref_model_name' => 'Parcel',
                'foreign_key' => 'waybill_number',
                'reference_key' => 'waybill_number'
            ],

            [
                'field' => 'from_branch',
                'model_name' => 'Parcel',
                'ref_model_name' => 'Branch',
                'foreign_key' => 'from_branch_id',
                'reference_key' => 'id',
                'alias' => 'ToBranch'
            ],

            [
                'field' => 'receiver_address',
                'model_name' => 'Parcel',
                'ref_model_name' => 'Address',
                'foreign_key' => 'receiver_address_id',
                'reference_key' => 'id'
            ],

            [
                'field' => 'receiver_address_city',
                'model_name' => 'Address',
                'ref_model_name' => 'City',
                'foreign_key' => 'city_id',
                'reference_key' => 'id'
            ],

            [
                'field' => 'receiver_address_state',
                'model_name' => 'Address',
                'ref_model_name' => 'State',
                'foreign_key' => 'state_id',
                'reference_key' => 'id'
            ]
        ];
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnCreate()
    {
        parent::beforeValidationOnCreate();
        $this->is_visible = 1;
    }
}