<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 06/10/15
 * Time: 2:31 PM
 */
class PickupContactValidation extends BaseValidation
{

    public function initialize()
    {
        $required_fields = ['address', 'state_id', 'city_id', 'name', 'phone_number'];
        $this->setRequiredFields($required_fields);

        $this->add('state_id', new Model(
            ['model' => 'State']
        ));

        $this->add('city_id', new Model(
            ['model' => 'City']
        ));
    }
}