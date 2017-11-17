<?php

use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\InlineValidator;

/**
 * Created by BulkInvoiceCreationValidation.php.
 * @author Rotimi Akintewe <rotimi.akintewe@konga.com>
 */
class BulkInvoiceCreationValidation extends BaseValidation
{


    /**
     * validations setups
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @return mixed
     */
    function initialize()
    {
        $this->setRequiredFields(['data']);

        $this->add('data', new InlineValidator([
            'function' => function () {
                return is_array($this->getValue('data'));
            },
            'message' => 'data must be an array of shipments'
        ]));
    }
}