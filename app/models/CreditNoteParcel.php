<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNoteParcel extends EagerModel
{
    /**
     * Validates that invoice parcels exist
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $invoiceNumber
     * @param $parcels
     * @return bool
     */
    public static function validateInvoiceParcels($invoiceNumber, $parcels)
    {
        $invoiceParcelIds = [];
        foreach ($parcels as $parcel) {
            $invoiceParcelIds[] = $parcel->invoice_parcel_id;
        }

        $foundInvoiceParcels = InvoiceParcel::query()
            ->inWhere('id', $invoiceParcelIds)
            ->andWhere('invoice_number = :invoice_number:', ['invoice_number' => $invoiceNumber])
            ->execute()->toArray();
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
     * Add Parcels for a credit note
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $creditNoteNumber
     * @param $parcels
     * @throws Exception
     */
    public static function addParcels($creditNoteNumber, $parcels)
    {
        $values = [];

        foreach ($parcels as $parcel) {
            $rowData = [];
            $rowData[] = $creditNoteNumber;
            $rowData[] = $parcel->invoice_parcel_id;
            $rowData[] = $parcel->deducted_amount;
            $rowData[] = $parcel->new_net_amount;
            $rowData[] = Util::getCurrentDateTime();
            $rowData[] = Util::getCurrentDateTime();
            $values[] = $rowData;
        }
        $batch = new Batch('credit_note_parcels');
        $batch->setRows(['credit_note_number', 'invoice_parcel_id', 'deducted_amount', 'new_net_amount', 'created_at', 'updated_at']);
        $batch->setValues($values);
        $batch->insert();
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

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $creditNoteNo
     * @return mixed|null
     */
    public static function getDetails($creditNoteNo)
    {
        $creditNoteParcel = new CreditNoteParcel();
        $builder = $creditNoteParcel->getModelsManager()->createBuilder();
        $builder->from('CreditNoteParcel');
        $builder->columns('CreditNoteParcel.*,InvoiceParcel.*');
        $builder->where("CreditNoteParcel.credit_note_number = '$creditNoteNo'");
        $builder->innerJoin('InvoiceParcel', 'InvoiceParcel.id = CreditNoteParcel.invoice_parcel_id');
        $creditNoteDetails = $builder->getQuery()->execute()->toArray();
        if ($creditNoteDetails) {
            return $creditNoteDetails;
        } else {
            return null;
        }
    }
}