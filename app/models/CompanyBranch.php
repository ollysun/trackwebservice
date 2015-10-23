<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CompanyBranch extends EagerModel
{
    /**
     * Fetches all company branches depending on the provided criteria
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with)
    {
        $obj = new CompanyBranch();
        $builder = $obj->getModelsManager()->createBuilder();
        $builder->from('CompanyBranch');

        if (!isset($fetch_with['no_paginate'])) {
            $builder->limit($count, $offset);
        }

        $columns = ['CompanyBranch.*'];
        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);
        $result = [];
        foreach ($data as $item) {
            if (isset($item->companyBranch)) {
                $companyBranch = $item->companyBranch->toArray();
                $relatedRecords = $obj->loadRelatedModels($item, true);

                $companyBranch = array_merge($companyBranch, $relatedRecords);
            } else {
                $companyBranch = $item->toArray();
            }
            $result[] = $companyBranch;
        }
        return $result;
    }

    /**
     *
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $filter_by
     * @return int|null
     */
    public static function getTotalCount($filter_by)
    {
        $obj = new CompanyBranch();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS company_branch_count')
            ->from('CompanyBranch');

        $filter_cond = self::filterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->company_branch_count);
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $filter_by
     * @return array
     */
    private static function filterConditions($filter_by)
    {
        $bind = [];
        $where = [];

        if (isset($filter_by['company_id'])) {
            $where[] = 'CompanyBranch.company_id = :company_id:';
            $bind['company_id'] = $filter_by['company_id'];
        }

        return ['where' => $where, 'bind' => $bind];
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('company_branches');
    }

    /**
     * Creates a new link between company and branch
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $companyId
     * @param $branchId
     * @return bool
     */
    public static function add($companyId, $branchId)
    {
        $companyBranch = new CompanyBranch();
        $companyBranch->company_id = $companyId;
        $companyBranch->branch_id = $branchId;
        return $companyBranch->save();
    }

    /**
     * Edit a link between company and branch
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $companyId
     * @param $branchId
     * @return bool
     */
    public static function edit($id, $companyId, $branchId)
    {
        $companyBranch = CompanyBranch::findFirst($id);

        if($companyBranch) {
            $companyBranch->company_id = $companyId;
            $companyBranch->branch_id = $branchId;
            return $companyBranch->save();
        }

        return false;
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
                    'field' => 'company',
                    'model_name' => 'CompanyBranch',
                    'ref_model_name' => 'Company',
                    'foreign_key' => 'company_id',
                    'reference_key' => 'id'
                ],
                [
                    'field' => 'branch',
                    'model_name' => 'CompanyBranch',
                    'ref_model_name' => 'Branch',
                    'foreign_key' => 'branch_id',
                    'reference_key' => 'id'
                ]
            ];
    }
}