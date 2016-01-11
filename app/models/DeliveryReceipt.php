<?php
use Phalcon\Di;
use Phalcon\Mvc\Model;

/**
 * Class DeliveryReceipt
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class DeliveryReceipt extends BaseModel
{
    const RECEIPT_TYPE_SNAPSHOT = 'snapshot';
    const RECEIPT_TYPE_SIGNATURE = 'signature';
    const RECEIPT_TYPE_RECEIVER_DETAIL = 'receiver_detail';

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        parent::initialize();
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

    /**
     * Create a delivery receipt
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $delivery_receipt = new DeliveryReceipt();
        foreach ($data as $key => $value) {
            $delivery_receipt->$key = $value;
        }

        if ($delivery_receipt->delivered_at == null) {
            $delivery_receipt->delivered_at = Util::getCurrentDateTime();
        }
        return $delivery_receipt->save();
    }

    /**
     * Get Base S3 Url
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public static function getS3BaseUrl()
    {
        $s3Config = Di::getDefault()->getConfig()->aws->s3;
        return  '//s3-' . $s3Config->region . '.amazonaws.com/' . $s3Config->bucket . '/' . $s3Config->namespace . '/';
    }
}