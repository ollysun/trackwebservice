<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;

/**
 * Class PickupRequestValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class PickupRequestValidation extends BaseValidation
{

    public function initialize()
    {
        $required_fields = ['pickup', 'destination', 'company_id'];

        $this->setRequiredFields($required_fields);

        $this->add('company_id', new Model([
            'model' => 'Company'
        ]));

        $this->add('created_by', new Model(
                [
                    'message' => 'User does not belong to the supplied company',
                    'model' => 'CompanyUser',
                    'conditions' => 'id = :id: AND company_id = :company_id:',
                    'bind' => ['id' => $this->getValue('created_by'), 'company_id' => $this->getValue('company_id')]
                ]
            )
        );
    }


}