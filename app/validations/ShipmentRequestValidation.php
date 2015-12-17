<?php

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
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
            'estimated_weight', 'no_of_packages'];

        foreach($required_fields as $required_field){
            $this->add($required_field, new PresenceOf([
                'cancelOnFail' => true,
                'message' => ucwords(str_replace('_', ' ', $required_field)). ' is required'
            ]));
        }

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

        $this->add('estimated_weight', new Regex([
            'pattern' => '/^((?=.*[1-9])\d+(\.\d+)?)$/',
            'message' => 'invalid :field supplied'
        ]));

        $this->add('no_of_packages', new Regex([
            'pattern' => '/^(?!0+$)\d+$/',
            'message' => 'invalid :field supplied'
        ]));

        if (!is_null($this->getValue('parcel_value'))) {
            $this->add('parcel_value', new Regex([
                'pattern' => '/^[\d]+([\.]?[\d]+)?$/',
                'message' => 'invalid :field supplied'
            ]));
        }
    }
}