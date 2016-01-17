<?php
use mikehaertl\wkhtmlto\Pdf;
use Phalcon\Mvc\Model\Resultset;
use Picqer\Barcode\BarcodeGeneratorHTML;

/**
 * Class BulkWaybillPrintingJob
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BulkWaybillPrintingJob extends BaseJob
{
    private $parcelTypes;
    private $shippingTypes;

    public function onStart()
    {
        parent::onStart();
        /** @var Resultset $parcelTypes */
        $parcelTypes = ParcelType::find();
        $this->parcelTypes = $this->getDataMap(['id' => 'name'], $parcelTypes->toArray());

        /** @var Resultset $shipmentTypes */
        $shippingTypes = ShippingType::find();
        $this->shippingTypes = $this->getDataMap(['id' => 'name'], $shippingTypes->toArray());
    }

    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function execute()
    {
        $waybill_numbers = $this->data->waybill_numbers;
        $waybills_html = '';
        foreach ($waybill_numbers as $waybill_number) {
            print 'Printing ' . $waybill_number . '...' . "\n";
            $waybills_html .= $this->getWaybillHtml($waybill_number);
        }

        $pdf = new Pdf();
        $waybill_layout = file_get_contents(dirname(__DIR__) . '/html/bulk_waybill_layout.html');
        $html_content = Util::replaceTemplate($waybill_layout, ['content' => $waybills_html]);
        $pdf->addPage($html_content);
        $pdf->saveAs(dirname(__DIR__) . '/html/bulk_waybill_layout.pdf');
        exit;
    }

    /**
     * Get Waybill Html
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @return string
     */
    private function getWaybillHtml($waybill_number)
    {
        $copies = ["Sender's Copy", "EC Copy", "Ack. Copy", "Recipient's Copy"];
        $waybill_html = '';
        $waybill_template = file_get_contents(dirname(__DIR__) . '/html/waybill_template.html');
        $parcel = Parcel::fetchOne($waybill_number, false, 'waybill_number');
        if (!$parcel) {
            return '';
        }

        $placeholderValues = $this->getPlaceholderValues($parcel);
        $waybill_template = Util::replaceTemplate($waybill_template, $placeholderValues);
        foreach ($copies as $i => $copy) {
            $waybill_html .= Util::replaceTemplate($waybill_template, ['copy' => $copy]);
            if ($i == 0 || $i == 2) {
                $waybill_html .= '<div class="waybill-divider"></div>';
            }
        }

        return $waybill_html;
    }

    /**
     * Get Placeholder values
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $parcel
     * @return array
     */
    private function getPlaceholderValues($parcel)
    {
        $generator = new BarcodeGeneratorHTML();
        $barCodeData = $generator->getBarcode($parcel['waybill_number'], BarcodeGeneratorHTML::TYPE_CODE_128, 2, 78);
        return [
            'waybill_number' => $parcel['waybill_number'],
            'sender_name' => $parcel['sender']['firstname'] . ' ' . $parcel['sender']['lastname'],
            'sender_address' => $parcel['sender_address']['street_address1'] .
                '<br/>' . $parcel['sender_address']['street_address2'] . '<br/>',
            'sender_country' => $parcel['sender_country']['name'],
            'sender_telephone' => $parcel['sender']['phone'],
            'sender_state' => ucwords($parcel['sender_state']['name']),
            'sender_city' => ucwords($parcel['sender_city']['name']),
            'receiver_name' => $parcel['receiver']['firstname'] . ' ' . $parcel['receiver']['lastname'],
            'receiver_address' => $parcel['receiver_address']['street_address1'] .
                '<br/>' . $parcel['receiver_address']['street_address2'] . '<br/>',
            'receiver_country' => $parcel['receiver_country']['name'],
            'receiver_telephone' => $parcel['receiver']['phone'],
            'receiver_state' => ucwords($parcel['receiver_state']['name']),
            'receiver_city' => ucwords($parcel['receiver_city']['name']),
            'shipping_day' => date('d', strtotime($parcel['created_date'])),
            'shipping_month' => date('m', strtotime($parcel['created_date'])),
            'shipping_year' => date('y', strtotime($parcel['created_date'])),
            'reference_number' => (!empty($parcel['reference_number']) ? 'REF:' . $parcel['reference_number'] : ''),
            'sender_city_code' => $parcel['sender_state']['code'],
            'receiver_city_code' => $parcel['receiver_state']['code'],
            'no_of_package' => $parcel['no_of_package'],
            'weight' => ($parcel['qty_metrics'] == Parcel::QTY_METRICS_WEIGHT) ? Util::formatWeight($parcel['weight']) . 'Kg' : '',
            'pieces' => ($parcel['qty_metrics'] == Parcel::QTY_METRICS_PIECES) ? $parcel['weight'] : '',
            'service_types' => $this->getServiceTypeHtml($parcel['shipping_type']),
            'parcel_type' => $this->shippingTypes[$parcel['parcel_type']],
            'cod_yes' => (($parcel['cash_on_delivery'] == '1') ? 'is-active' : ''),
            'cod_no' => (($parcel['cash_on_delivery'] == '1') ? '' : 'is-active'),
            'other_info' => $parcel['other_info'],
            'barcode_data' => $barCodeData
        ];
    }

    /**
     * Map data as give in map
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $map
     * @param $array
     * @return array
     */
    private function getDataMap($map, $array)
    {
        $dataMap = [];
        foreach ($map as $key => $value) {
            foreach ($array as $element) {
                $dataMap[$element[$key]] = $element[$value];
            }
        }

        return $dataMap;
    }

    /**
     * Get service type html
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $service_type
     * @return string
     */
    public function getServiceTypeHtml($service_type)
    {
        $serviceTypeHtml = '';
        foreach ($this->shippingTypes as $id => $name) {
            $class = ($service_type == $id) ? 'service-type__inner is-active' : 'service-type__inner';
            $serviceTypeHtml .= "<div class='{$class}'><span>" . ucwords($name) . "</span></div>";
        }
        return $serviceTypeHtml;
    }
}