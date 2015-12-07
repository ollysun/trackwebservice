<?php
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
        $filter_cond = self::getFilterConditions(['created_by' => $created_by]);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];
        $obj->setFetchWith(['with_to_branch'])->joinWith($builder, $columns);

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
            ]
        ];
    }
}