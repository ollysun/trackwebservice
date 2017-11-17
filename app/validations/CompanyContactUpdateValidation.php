<?php
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\InclusionIn;
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;
use PhalconUtils\Validation\Validators\NigerianPhoneNumber;
use PhalconUtils\Validation\Validators\NotExisting;

/**
 * Class CompanyContactValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class CompanyContactUpdateValidation extends BaseValidation
{

    public function initialize()
    {
        $this->setRequiredFields(['id', 'company_id', 'firstname', 'lastname', 'phone_number', 'email', 'user_auth_id', 'role_id']);

        $this->add('id', new Model([
            'model' => CompanyUser::class
        ]));

        $this->add('company_id', new Model([
            'model' => Company::class
        ]));

        $this->add('user_auth_id', new Model([
            'model' => UserAuth::class
        ]));

        $this->add('email', new NotExisting([
            'model' => UserAuth::class,
            'conditions' => 'email = :email: AND id <> :id:',
            'bind' => [
                'email' => $this->getValue('email'),
                'id' => $this->getValue('user_auth_id')
            ],
            'message' => ResponseMessage::COMPANY_USER_ALREADY_EXISTS
        ]));

        $this->add('email', new Email([
            'message' => ':field is not valid'
        ]));

        $this->add('phone_number', new NigerianPhoneNumber());

        $this->add('role_id', new Model([
            'model' => Role::class
        ]));

        $this->add('status', new InclusionIn([
            'domain' => [Status::ACTIVE, Status::INACTIVE]
        ]));
    }
}