<?php

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

        $filter_cond = self::filterConditions(['created_by' => $created_by]);
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
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $filter_by
     * @return null
     */
    public static function getTotalCount($filter_by)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS total_count')
            ->from('ParcelDraftSort');

        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $count = $builder->getQuery()->getSingleResult($bind);
        return empty($count) ? null : $count;
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
            $sort_number = 'S' . $to_branch . $created_by . time();
            $draftParcelSort->sort_number = $sort_number;
        }
    }

    /**
     * filter conditions
     * @author Rahman Shitu <yemi@cottacush.com>
     * @param $filter_by
     * @return array
     */
    private static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];
        if (isset($filter_by['created_by'])) {
            $where[] = 'created_by = :created_by:';
            $bind['created_by'] = $filter_by['created_by'];
        }
        return ['where' => $where, 'bind' => $bind];
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
     * @author Adegoke Obasa <goke@cottacush.com>
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