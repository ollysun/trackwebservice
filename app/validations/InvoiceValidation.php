<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\NotExisting;

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
    }
}