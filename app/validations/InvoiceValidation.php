<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceValidation extends BaseValidation
{

    /**
     * validations setups
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return mixed
     */
    function initialize()
    {
        $this->setRequiredFields(['address', 'to_address', 'currency', 'reference', 'parcels']);

        $this->add('company_id', new Model([
            'model' => Company::class,
            'message' => 'Company does not exists'
        ]));
    }
}