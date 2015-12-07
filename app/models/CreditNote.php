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
     * Generates credit note number using microtime with prefix
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return string
     */
    public static function generateCreditNumber()
    {
        $time = microtime(true) * 10000;
        return 'CN-' . $time;
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