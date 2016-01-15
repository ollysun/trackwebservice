<?php
use mikehaertl\wkhtmlto\Pdf;

/**
 * Class BulkWaybillPrintingJob
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BulkWaybillPrintingJob extends BaseJob
{
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
            print 'Printing '.$waybill_number.'...'."\n";
            $waybills_html .= $this->getWaybillHtml($waybill_number);
        }

        $pdf = new Pdf();
        $waybill_layout = file_get_contents(dirname(__DIR__) . '/html/bulk_waybill_layout.html');
        $pdf_content = Util::replaceTemplate($waybill_layout, ['content' => $waybills_html]);
        $pdf->addPage($pdf_content);
        return $pdf->saveAs(dirname(__DIR__) . '/html/bulk_waybill_layout.pdf');
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
        $params = [
            'waybill_number' => $parcel['waybill_number'],
            'sender_name' => $parcel['sender']['firstname'] . ' ' . $parcel['sender']['lastname'],
            'sender_address' => $parcel['sender_address']['street_address1'] .
                '<br/>' . $parcel['sender_address']['street_address2'] . '<br/>',
            'sender_country' => $parcel['sender_country']['name'],
            'sender_telephone' => $parcel['sender']['phone'],
            'receiver_name' => $parcel['receiver']['firstname'] . ' ' . $parcel['receiver']['lastname'],
            'receiver_address' => $parcel['receiver_address']['street_address1'] .
                '<br/>' . $parcel['receiver_address']['street_address2'] . '<br/>',
            'receiver_country' => $parcel['receiver_country']['name'],
            'receiver_telephone' => $parcel['receiver']['phone'],
            'shipping_day' => date('d', strtotime($parcel['created_date'])),
            'shipping_month' => date('m', strtotime($parcel['created_date'])),
            'shipping_year' => date('y', strtotime($parcel['created_date'])),
            'reference_number' => (!empty($parcel['reference_number']) ? 'REF:' . $parcel['reference_number'] : ''),
            'sender_city_code' => $parcel['sender_state']['code'],
            'receiver_city_code' => $parcel['receiver_state']['code'],
            'no_of_package' => $parcel['no_of_package'],
            'weight' => ($parcel['qty_metrics'] == Parcel::QTY_METRICS_WEIGHT) ? Util::formatWeight($parcel['weight']) . 'Kg' : '',
            'pieces' => ($parcel['qty_metrics'] == Parcel::QTY_METRICS_PIECES) ? $parcel['weight'] : '',
            'service_types' => '',
            'parcel_type' => '',
            'cod_yes' => (($parcel['cash_on_delivery'] == '1') ? 'is-active' : ''),
            'cod_no' => (($parcel['cash_on_delivery'] == '1') ? '' : 'is-active'),
            'other_info' => $parcel['other_info']
        ];

        $waybill_template = Util::replaceTemplate($waybill_template, $params);
        foreach ($copies as $copy) {
            $waybill_html .= Util::replaceTemplate($waybill_template, ['copy' => $copy]);
        }

        return $waybill_html;
    }
}