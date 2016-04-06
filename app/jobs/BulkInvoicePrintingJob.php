<?php

/**
 * Created by BulkInvoicePrintingJob.php.
 * @author Rotimi Akintewe <rotimi.akintewe@konga.com>
 */
class BulkInvoicePrintingJob extends BaseJob
{

    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function execute()
    {
        $jobData = $this->data;
        $jobInvoiceData = $jobData->data;

        if (!$this->jobLog) {
            return false;
        }

        $jobStatus = false;

        foreach ($jobInvoiceData as $invoiceData) {

            $bulkInvoiceJobDetail = new BulkInvoiceJobDetails();
            $bulkInvoiceJobDetail->job_id = $this->jobLog->id;
            $bulkInvoiceJobDetail->data = json_encode($invoiceData);
            $bulkInvoiceJobDetail->status = BulkInvoiceJobDetails::STATUS_IN_PROGRESS;
            $bulkInvoiceJobDetail->started_at = Util::getCurrentDateTime();
            $bulkInvoiceJobDetail->save();
            try {
                $invoice = $this->createInvoice($invoiceData);
                if (!$invoice) {
                    $bulkInvoiceJobDetail->status = BulkInvoiceJobDetails::STATUS_FAILED;
                } else {
                    $bulkInvoiceJobDetail->company_id = $invoiceData->company_id;
                    $bulkInvoiceJobDetail->invoice_number = $invoice;
                    $bulkInvoiceJobDetail->status = BulkInvoiceJobDetails::STATUS_SUCCESS;
                }
            } catch (Exception $ex) {
                $bulkInvoiceJobDetail->status = BulkInvoiceJobDetails::STATUS_FAILED;
                $bulkInvoiceJobDetail->error_message = $ex->getMessage();
                print $ex->getMessage() . "\n";
            }

            $bulkInvoiceJobDetail->completed_at = Util::getCurrentDateTime();
            $bulkInvoiceJobDetail->save();
            $jobStatus = ($bulkInvoiceJobDetail->status == BulkInvoiceJobDetails::STATUS_SUCCESS) || $jobStatus;
        }

        return $jobStatus;
    }

    public function createInvoice($invoiceData)
    {
        // Generate Invoice Number
        $invoiceData->invoice_number = Invoice::generateInvoiceNumber();
        $invoice = Invoice::generate((array)$invoiceData);

        if ($invoice) {
            // Add Invoice Parcels
            if (!InvoiceParcel::validateParcels($invoiceData->parcels)) {
                throw new Exception(ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST);
            }

            if (!InvoiceParcel::validateInvoiceParcel($invoiceData->parcels)) {
                throw new Exception(ResponseMessage::INVOICE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS);
            }

            InvoiceParcel::addParcels($invoiceData->invoice_number, $invoiceData->parcels);

            print 'Invoice ' . $invoiceData->invoice_number . ' successfully created' . "\n";
            return $invoiceData->invoice_number;
        } else {
            throw new Exception(Invoice::getLastErrorMessage());
        }
    }
}