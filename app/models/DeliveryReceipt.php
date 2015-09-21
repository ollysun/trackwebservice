<?php
use Phalcon\Mvc\Model;

/**
 * Class DeliveryReceipt
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class DeliveryReceipt extends Model
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('delivery_receipts');
    }

    /**
     * Check if delivery receipt already exist
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @param $receipt_type
     * @return bool
     */
    public static function doesReceiptExist($waybill_number, $receipt_type)
    {
        $receipt = self::findFirst(['conditions' => 'waybill_number = :waybill_number: AND receipt_type =:receipt_type:',
            'bind' => ['waybill_number' => $waybill_number, 'receipt_type' => $receipt_type]]);
        return ($receipt) ? true : false;
    }

    public function beforeValidationOnCreate()
    {
        $this->created_at = Util::getCurrentDateTime();
    }
}