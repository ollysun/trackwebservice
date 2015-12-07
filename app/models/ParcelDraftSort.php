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
     * @return array
     */
    public static function getDraftSorts($created_by, $count, $offset)
    {
        $obj = new ParcelDraftSort();
        $builder = $obj->getModelsManager()->createBuilder();
        $builder->from('ParcelDraftSort');
        $builder->limit($count, $offset);
        $columns = ['ParcelDraftSort.*'];

        $filter_cond = self::getFilterConditions(['created_by' => $created_by]);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];
        $obj->setFetchWith(['to_branch'])->joinWith($builder, $columns);

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        $result = [];
        foreach ($data as $item) {
            if (isset($item->parcelDraftSort)) {
                $parcelDraftSort = $item->parcelDraftSort->getData();
                $relatedRecords = $obj->loadRelatedModels($item, true);
                $parcelDraftSort = array_merge($parcelDraftSort, $relatedRecords);
            } else {
                $parcelDraftSort = $item->getData();
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
     */
    public static function createDraftParcelSorts($waybill_numbers, $to_branch, $created_by)
    {
        foreach ($waybill_numbers as $waybill_number) {
            $draftParcelSort = new ParcelDraftSort();
            $draftParcelSort->waybill_number = $waybill_number;
            $draftParcelSort->to_branch = $to_branch;
            $draftParcelSort->created_by = $created_by;
            $sort_number = 'DS' . $to_branch . $created_by . time();
            $draftParcelSort->sort_number = $sort_number;
            $draftParcelSort->save();
        }
    }

    private static function createDraftParceSort($waybill_number, $to_branch, $created_by)
    {
        $draftParcelSort = new ParcelDraftSort();
        if (!Parcel::isWaybillNumber($waybill_number)) {
            throw new Exception('Invalid waybill number');
        }


        $draftParcelSort->waybill_number = $waybill_number;
        $draftParcelSort->to_branch = $to_branch;
        $draftParcelSort->created_by = $created_by;
        $sort_number = 'DS' . $to_branch . $created_by . time();
        $draftParcelSort->sort_number = $sort_number;
        $draftParcelSort->save();
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
            ]
        ];
    }
}