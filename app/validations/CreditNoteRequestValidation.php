<?php
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\Validators\Model;
use PhalconUtils\Validation\Validators\NotExisting;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNoteRequestValidation extends BaseValidation
{

    /**
     * validations setups
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return mixed
     */
    function initialize()
    {
        $this->setRequiredFields(['invoice_number', 'parcels']);

        $this->add('invoice_number', new Model([
            'model' => Invoice::class,
            'message' => 'The invoice does not exist',
            'conditions' => 'invoice_number = :invoice_number:',
            'bind' => [
                'invoice_number' => $this->getValue('invoice_number')
            ]
        ]));

        $this->add('invoice_number', new NotExisting([
            'model' => CreditNote::class,
            'message' => 'A credit note already exists for this invoice number',
            'conditions' => 'invoice_number = :invoice_number:',
            'bind' => [
                'invoice_number' => $this->getValue('invoice_number')
            ]
        ]));
    }
}