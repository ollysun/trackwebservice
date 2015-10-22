<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CompanyBranch extends EagerModel
{

    /**
     * Creates a new link between company and branch
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $companyId
     * @param $branchId
     * @return bool
     */
    public function add($companyId, $branchId)
    {
        $companyBranch = new CompanyBranch();
        $companyBranch->company_id = $companyId;
        $companyBranch->branch_id = $branchId;
        return $companyBranch->save();
    }

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [];
    }
}