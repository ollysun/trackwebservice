<?php

use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;

/**
 * Class ShipmentRequestValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ShipmentRequestValidation extends BaseValidation
{

    /**
     * validations setups
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    function initialize()
    {
        $required_fields = ['receiver_firstname',
            'receiver_address', 'company_id',
            'receiver_state_id', 'receiver_city_id',
            'estimated_weight', 'no_of_packages',
            'parcel_value'];

        $this->setRequiredFields($required_fields);

        $this->add('company_id', new Model([
            'model' => Company::class,
            'message' => ResponseMessage::INVALID_COMPANY_ID_SUPPLIED
        ]));

        $this->add('receiver_state_id', new Model([
            'model' => State::class,
            'message' => ResponseMessage::INVALID_RECEIVER_STATE_SUPPLIED
        ]));

        $this->add('receiver_city_id', new Model([
            'model' => City::class,
            'message' => ResponseMessage::INVALID_RECEIVER_CITY_SUPPLIED
        ]));

        $this->add('company_id', new Model([
            'model' => Company::class,
            'message' => ResponseMessage::INVALID_COMPANY_ID_SUPPLIED
        ]));

        $this->add('created_by', new Model([
            'model' => CompanyUser::class,
            'conditions' => 'id = :id: AND company_id = :company_id:',
            'bind' => ['id' => $this->getValue('created_by'), 'company_id' => $this->getValue('company_id')],
            'message' => 'Invalid company user'
        ]));
    }
}