<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;
use PhalconUtils\Validation\Validators\NotExisting;

/**
 * Class CompanyRequestValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class CompanyRequestValidation extends BaseValidation
{
    public function initialize()
    {
        $this->setRequiredFields(['name', 'reg_no', 'email', 'phone_number', 'address', 'relations_officer_id', 'city_id']);

        $this->add('city_id', new Model([
            'model' => City::class
        ]));

        $this->add('name', new NotExisting([
            'model' => Company::class,
            'conditions' => 'name = :name:',
            'message' => ResponseMessage::COMPANY_EXISTING,
            'bind' => ['name' => $this->getValue('name')]
        ]));

        $this->add('relations_officer_id', new Model([
            'model' => Admin::class,
            'message' => ResponseMessage::INVALID_RELATIONS_OFFICER_ID
        ]));

    }
}