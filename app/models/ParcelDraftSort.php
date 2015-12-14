<?php
use Phalcon\Di;
use Phalcon\Exception;

/**
 * Class ParcelDraftSort
 * @property  string waybill_number
 * @property  int to_branch
 * @property  int created_by
 * @property string sort_number
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

        return $draftSortParcel->delete();
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
     * @inheritdoc
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('parcel_draft_sorts');
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
}