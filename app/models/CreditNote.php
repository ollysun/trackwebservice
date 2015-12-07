<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNote extends EagerModel
{
    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('credit_notes');
    }

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [
            [
                'field' => 'invoice',
                'model_name' => self::class,
                'ref_model_name' => 'Invoice',
                'foreign_key' => 'invoice_number',
                'reference_key' => 'invoice_number'
            ]
        ];
    }
}