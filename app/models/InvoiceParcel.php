<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceParcel extends BaseModel
{

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('invoice_parcels');
    }

    /**
     * Add Parcels for an Invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $invoiceNumber
     * @param $parcels
     * @throws Exception
     */
    public static function addParcels($invoiceNumber, $parcels)
    {
        $values = [];

        foreach($parcels as $parcel) {
            $rowData = [];
            $rowData[] = $invoiceNumber;
            $rowData[] = $parcel->waybill_number;
            $rowData[] = $parcel->discount;
            $rowData[] = $parcel->net_amount;
            $rowData[] = Util::getCurrentDateTime();
            $rowData[] = Util::getCurrentDateTime();
        }
        $batch = new Batch('invoice_parcels');
        $batch->setRows(['invoice_number', 'waybill_number', 'discount', 'net_amount', 'created_at', 'updated_at']);
        $batch->setValues($values);
        $batch->insert();
    }
}