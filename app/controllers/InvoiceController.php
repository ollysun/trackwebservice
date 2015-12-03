<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceController extends ControllerBase
{

    /**
     * Adds a new invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();

        $invoiceRequestValidator = new InvoiceValidation($postData);
        if (!$invoiceRequestValidator->validate()) {
            return $this->response->sendError($invoiceRequestValidator->getMessages());
        }

        // Generate Invoice Number
        $postData->invoice_number = Invoice::generateInvoiceNumber();
        $invoice = Invoice::generate((array)$postData);

        if ($invoice) {
            // Add Invoice Parcels
            //TODO validate waybill numbers

            if (!InvoiceParcel::validateParcels($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST);
            }

            if(!InvoiceParcel::validateInvoiceParcel($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::INVOICE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS);
            }

            InvoiceParcel::addParcels($postData->invoice_number, $postData->parcels);

            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_INVOICE);
    }

    /**
     * Get paginated list of invoices
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getAllAction()
    {

    }
}