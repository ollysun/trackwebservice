<?php
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex;
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;
use PhalconUtils\Validation\Validators\NigerianPhoneNumber;
use PhalconUtils\Validation\Validators\NotExisting;

/**
 * Class CompanyUpdateRequestValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CompanyUpdateRequestValidation extends BaseValidation
{
    public function initialize()
    {
        $this->setRequiredFields(['id', 'name', 'email', 'phone_number', 'address', 'relations_officer_id', 'city_id']);

        $this->add('id', new Model([
            'model' => Company::class
        ]));

        $this->add('city_id', new Model([
            'model' => City::class
        ]));

        $this->add('name', new Regex([
            'pattern' => '/.*[a-z]+.*/i',
            'message' => 'Invalid company name'
        ]));

        $this->add('name', new NotExisting([
            'model' => Company::class,
            'conditions' => 'name = :name: AND id <> :id:',
            'message' => ResponseMessage::COMPANY_EXISTING,
            'bind' => [
                'name' => $this->getValue('name'),
                'id' => $this->getValue('id')
            ]
        ]));

        $this->add('relations_officer_id', new Model([
            'model' => Admin::class,
            'message' => ResponseMessage::INVALID_RELATIONS_OFFICER_ID
        ]));

        $this->add('email', new Email([
            'message' => ':field is not valid'
        ]));

        $this->add('phone_number', new NigerianPhoneNumber());
    }
}