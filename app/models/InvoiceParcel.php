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

        foreach ($parcels as $parcel) {
            $rowData = [];
            $rowData[] = $invoiceNumber;
            $rowData[] = $parcel->waybill_number;
            $rowData[] = $parcel->discount;
            $rowData[] = $parcel->net_amount;
            $rowData[] = Util::getCurrentDateTime();
            $rowData[] = Util::getCurrentDateTime();
            $values[] = $rowData;
        }
        $batch = new Batch('invoice_parcels');
        $batch->setRows(['invoice_number', 'waybill_number', 'discount', 'net_amount', 'created_at', 'updated_at']);
        $batch->setValues($values);
        $batch->insert();
    }

    /**
     * Validates that a parcel exists
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $parcels
     * @return bool
     */
    public static function validateParcels($parcels)
    {
        $waybillNumbers = [];
        foreach ($parcels as $parcel) {
            $waybillNumbers[] = $parcel->waybill_number;
        }
        // Check if parcels exist
        $foundParcels = Parcel::query()->inWhere('waybill_number', $waybillNumbers)->execute()->toArray();

        return count($foundParcels) == count($parcels);
    }

    /**
     * Validates that no invoice exists for parcels
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $parcels
     * @return bool
     */
    public static function validateInvoiceParcel($parcels)
    {
        $waybillNumbers = [];
        foreach ($parcels as $parcel) {
            $waybillNumbers[] = $parcel->waybill_number;
        }

        $foundInvoiceParcels = InvoiceParcel::query()->inWhere('waybill_number', $waybillNumbers)->execute()->toArray();
        return empty($foundInvoiceParcels);
    }
}