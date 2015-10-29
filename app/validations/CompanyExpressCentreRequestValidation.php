<?php
use PhalconUtils\Validation\Validators\Model;
use PhalconUtils\Validation\Validators\NotExisting;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CompanyExpressCentreRequestValidation extends \PhalconUtils\Validation\BaseValidation
{
    /**
     * validations setups
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    function initialize()
    {
        $this->setRequiredFields(['branch_id', 'company_id']);

        $this->add('company_id', new Model([
            'model' => Company::class,
            'message' => 'Company does not exists',
        ]));

        $this->add('branch_id', new Model([
            'model' => Branch::class,
            'message' => 'Express centre does not exists'
        ]));

        $this->add('branch_id', new Model([
            'model' => Branch::class,
            'message' => 'Branch is not an express centre',
            'conditions' => 'id = :id: AND branch_type = :branch_type:',
            'bind' => [
                'id' => $this->getValue('branch_id'),
                'branch_type' => BranchType::EC
            ]
        ]));

        $this->add('name', new NotExisting([
            'model' => CompanyBranch::class,
            'conditions' => 'branch_id = :branch_id: AND company_id <> :company_id:',
            'message' => 'Express centre already linked to a company',
            'bind' => [
                'branch_id' => $this->getValue('branch_id'),
                'company_id' => $this->getValue('company_id')
            ]
        ]));

        $this->add('name', new NotExisting([
            'model' => CompanyBranch::class,
            'conditions' => 'branch_id = :branch_id: AND company_id = :company_id:',
            'message' => 'Express centre already linked to this company',
            'bind' => [
                'branch_id' => $this->getValue('branch_id'),
                'company_id' => $this->getValue('company_id')
            ]
        ]));
    }
}