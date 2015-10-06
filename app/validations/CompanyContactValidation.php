<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\NotExisting;

/**
 * Class CompanyContactValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class CompanyContactValidation extends BaseValidation
{

    public function initialize()
    {
        $this->setRequiredFields(['firstname', 'lastname', 'phone_number', 'email']);

        $this->add('email', new NotExisting([
            'model' => UserAuth::class,
            'conditions' => 'email = :email:',
            'bind' => ['email' => $this->getValue('email')],
            'message' => ($this->getNamespace() == 'primary_contact') ? ResponseMessage::PRIMARY_CONTACT_EXISTS : ResponseMessage::SECONDARY_CONTACT_EXISTS
        ]));

    }
}