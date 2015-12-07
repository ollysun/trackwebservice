<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNoteParcel extends EagerModel
{
    /**
     * Validates that invoice parcels exist
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $parcels
     * @return bool
     */
    public static function validateInvoiceParcels($parcels)
    {
        $invoiceParcelIds = [];
        foreach ($parcels as $parcel) {
            $invoiceParcelIds[] = $parcel->invoice_parcel_id;
        }

        $foundInvoiceParcels = InvoiceParcel::query()->inWhere('id', $invoiceParcelIds)->execute()->toArray();
        return count($foundInvoiceParcels) == count($parcels);
    }

    /**
     * Validates that the credit note parcel does not exists
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $parcels
     * @return bool
     */
    public static function validateCreditNoteParcel($parcels)
    {
        $invoiceParcelIds = [];
        foreach ($parcels as $parcel) {
            $invoiceParcelIds[] = $parcel->invoice_parcel_id;
        }

        $foundCreditNoteParcels = CreditNoteParcel::query()->inWhere('invoice_parcel_id', $invoiceParcelIds)->execute()->toArray();
        return empty($foundCreditNoteParcels);
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('credit_note_parcels');
    }

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [
            []
        ];
    }
}