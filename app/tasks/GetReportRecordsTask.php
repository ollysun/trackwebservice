<?php

/**
 * @author Babatunde Otaru <tunde@cottacush.com>
 */
class GetReportRecordsTask extends BaseTask
{

    const ACTIVE = 1;
    const INACTIVE = 2;
    const REMOVED = 3;
    const COLLECTED = 4;
    const IN_TRANSIT = 5;
    const DELIVERED = 6;
    const CANCELLED = 7;
    const FOR_SWEEPER = 8;
    const FOR_ARRIVAL = 9;
    const FOR_DELIVERY = 10;
    const UNCLEARED = 11;
    const CLEARED = 12;
    const BEING_DELIVERED = 13;
    const ASSIGNED_TO_GROUNDSMAN = 17;
    const MANIFEST_PENDING = 18;
    const MANIFEST_IN_TRANSIT = 19;
    const MANIFEST_RESOLVED = 20;
    const MANIFEST_CANCELLED = 21;
    const MANIFEST_HAS_ISSUE = 22;
    const RETURNED = 23;

    const DATE_TIME_FORMAT = 'd M Y H:i';

    const REF_PAYMENT_METHOD_CASH = 1;
    const REF_PAYMENT_METHOD_POS = 2;
    const REF_PAYMENT_METHOD_CASH_POS = 3;
    const REF_PAYMENT_METHOD_DEFERRED = 4;

    const DELIVERY_DISPATCH = 2;
    const DELIVERY_PICKUP = 1;
    const COUNTRY_NIGERIA = 1;

    const REQUEST_OTHERS = 1;
    const REQUEST_ECOMMERCE = 2;

    const SHIPPING_TYPE_EXPRESS = 1;
    const SHIPPING_TYPE_SPECIAL_PROJECTS = 2;
    const SHIPPING_TYPE_LOGISTICS = 3;
    const SHIPPING_TYPE_BULK_MAIL = 4;

    const PARCEL_DOCUMENTS = 1;
    const PARCEL_NON_DOCUMENTS = 2;
    const PARCEL_HIGH_VALUE = 3;


    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     */
    public function mainAction(array $dates)
    {
        $line = '';
        $headers = array('SN', 'Waybill Number', 'Sender', 'Sender Email', 'Sender Phone', 'Sender Address', 'Sender City', 'Sender State', 'Receiver', 'Receiver Email', 'Receiver Phone', 'Receiver Address', 'Receiver City', 'Receiver State', 'Weight/Piece', 'Payment Method', 'Amount Due', 'Cash Amount', 'POS Amount', 'POS Transaction ID', 'Parcel Type', 'Cash on Delivery', 'Delivery Type', 'Package Value', '# of Package', 'Shipping Type', 'Created Date', 'Last Modified Date', 'Status', 'Reference Number', 'Originating Branch', 'Route', 'Request Type', 'For Return', 'Other Info', 'Company Reg No', 'Billing Plan Name');
        foreach ($headers as $header) {
            $line .= $header . ' , ';
        }
        file_put_contents('public/reports.csv', $line . "\r\n", FILE_APPEND);
        $fetch_with = [];
        $filter_by = [];
        $extra_details = ['with_to_branch', 'with_from_branch', 'with_sender', 'with_sender_address', 'with_receiver', 'with_receiver_address', 'with_bank_account', 'with_created_branch', 'with_route', 'with_created_by', 'with_company'];
        foreach ($extra_details as $extra) {
            $fetch_with[$extra] = true;
        }
        $filter_by['send_all'] = true;
        $filter_by['start_created_date'] = $dates[0];
        $filter_by['end_created_date'] = $dates[1];

        $parcels = Parcel::fetchAll(0, 0, $filter_by, $fetch_with);

        $i = 1;
        foreach ($parcels as $parcel) {
            file_put_contents('public/reports.csv', $i++ . ' , ' .
                $parcel['waybill_number'] . ' , ' .
                $parcel['sender']['firstname'] . '  ' . $parcel['sender']['lastname'] . ' , ' .
                $parcel['sender']['email'] . ' , ' .
                $parcel['sender']['phone'] . ' , ' .
                $parcel['sender_address']['street_address1'] . ' , ' .
                $parcel['sender_address']['city']['name'] . ' , ' .
                $parcel['sender_address']['state']['name'] . ' , ' .
                $parcel['receiver']['firstname'] . $parcel['receiver']['lastname'] . ' , ' .
                $parcel['receiver']['email'] . ' , ' .
                $parcel['receiver']['phone'] . ' , ' .
                $parcel['receiver_address']['street_address1'] . ' , ' .
                $parcel['receiver_address']['city']['name'] . ' , ' .
                $parcel['receiver_address']['state']['name'] . ' , ' .
                $parcel['weight'] . ' , ' .
                self::getPaymentMethod($parcel['payment_type']) . ' , ' .
                $parcel['amount_due'] . ' , ' .
                $parcel['cash_amount'] . ' , ' .
                $parcel['pos_amount'] . ' , ' .
                $parcel['pos_trans_id'] . ' , ' .
                self::getParcelType($parcel['parcel_type']) . ' , ' .
                $parcel['parcel_type'] . ' , ' .
                (isset($parcel['cash_on_delivery']) ? "Yes" : "No") . ' , ' .
                self::getDeliveryType($parcel['delivery_type']) . ', ' .
                $parcel['package_value'] . ' , ' .
                $parcel['no_of_package'] . ' , ' .
                self::getShippingType($parcel['shipping_type']) . ' , ' .
                self::convertToTrackingDateFormat($parcel['created_date']) . ' , ' .
                self::formatDate(self::DATE_TIME_FORMAT, $parcel['modified_date']) . ' , ' .
                strip_tags(self::getStatus($parcel['status'])) . ' , ' .
                $parcel['reference_number'] . '  ' .
                (isset($parcel['created_branch']) ? $parcel['created_branch']['name'] : '') . ' , ' .
                (isset($parcel['route']) ? $parcel['route']['name'] : '') . ' , ' .
                self::getRequestType($parcel['request_type']) . ' , ' .
                ($parcel['for_return'] ? 'Yes' : 'No') . ' , ' .
                $parcel['other_info'] . ' , ' .
                $parcel['company']['reg_no'] . ' , ' .
                $parcel['billing_plan']['name'] . "\r\n", FILE_APPEND);
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $method
     * @return string
     */
    public static function getPaymentMethod($method)
    {
        switch ($method) {
            case self::REF_PAYMENT_METHOD_CASH:
                return 'Cash';
                break;

            case self::REF_PAYMENT_METHOD_POS:
                return 'POS';
                break;

            case self::REF_PAYMENT_METHOD_CASH_POS:
                return 'Cash & POS';
                break;

            case self::REF_PAYMENT_METHOD_DEFERRED:
                return 'Deferred Payment';
                break;

            default:
                return $method; // return id
                break;
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $type
     * @return bool|string
     */
    public static function getParcelType($type)
    {
        switch ($type) {
            case self::PARCEL_DOCUMENTS:
                return 'Documents';

            case self::PARCEL_HIGH_VALUE:
                return 'High Value';

            case self::PARCEL_NON_DOCUMENTS:
                return 'Non Documents';

            default:
                return false;
                break;

        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $type
     * @return bool|string
     */
    public static function getDeliveryType($type)
    {
        switch ($type) {
            case self::DELIVERY_DISPATCH:
                return 'Dispatch';

            case self::DELIVERY_PICKUP:
                return 'Pickup';

            default:
                return false;
        }
    }

    /**
     * Returns the shipping type in text
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $type
     * @return string
     */
    public static function getShippingType($type)
    {
        switch ($type) {
            case self::SHIPPING_TYPE_EXPRESS:
                return 'Express';

            case self::SHIPPING_TYPE_SPECIAL_PROJECTS:
                return 'Special Projects';

            case self::SHIPPING_TYPE_LOGISTICS:
                return 'Logistics';

            case self::SHIPPING_TYPE_BULK_MAIL:
                return 'Bulk Mail';

            default:
                return false;
                break;

        }
    }


    /**
     * Format date
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $format
     * @param $datetime
     * @return bool|string
     */
    public static function formatDate($format, $datetime)
    {
        $datetime = strtotime($datetime);
        if (!$datetime) {
            return '';
        }
        return date($format, $datetime);
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $datetime
     * @return bool|string
     */
    public static function convertToTrackingDateFormat($datetime)
    {
        return self::formatDate('d M. Y', $datetime);
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $status
     * @return string
     */
    public static function getStatus($status)
    {
        switch ($status) {
            case self::ACTIVE:
                return 'Active';
                break;
            case self::INACTIVE:
                return 'Inactive';
                break;
            case self::IN_TRANSIT:
                return 'In Transit';
                break;
            case self::REMOVED:
                return 'Removed';
                break;
            case self::COLLECTED:
                return 'Collected';
                break;
            case self::DELIVERED:
                return 'Delivered';
                break;
            case self::CANCELLED:
                return 'Cancelled';
                break;
            case self::FOR_SWEEPER:
                return 'For Sweeper';
                break;
            case self::FOR_ARRIVAL:
                return 'For Arrival';
                break;
            case self::FOR_DELIVERY:
                return 'For Delivery';
                break;
            case self::BEING_DELIVERED:
                return 'In Transit to Customer';
                break;
            case self::ASSIGNED_TO_GROUNDSMAN:
                return 'Assigned to Groundsman';
                break;
            case self::MANIFEST_PENDING:
                return 'Pending';
                break;
            case self::MANIFEST_IN_TRANSIT:
                return 'In Transit';
                break;
            case self::MANIFEST_HAS_ISSUE:
                return 'Has Issue';
                break;
            case self::MANIFEST_RESOLVED:
                return 'Resolved';
                break;
            case self::MANIFEST_CANCELLED:
                return 'Cancelled';
                break;
            case self::RETURNED:
                return 'Returned to Shipper';
                break;
        }
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $type
     * @return bool|string
     */
    public static function getRequestType($type)
    {
        switch ($type) {
            case self::REQUEST_ECOMMERCE:
                return 'eCommerce';

            case self::REQUEST_OTHERS:
                return 'Others';

            default:
                return false;
        }
    }
}