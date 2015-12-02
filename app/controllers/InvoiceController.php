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
        if(!$invoiceRequestValidator->validate()) {
            return $this->response->sendError($invoiceRequestValidator->getMessages());
        }

        // Generate Invoice Number
        $postData->invoice_number =  Invoice::generateInvoiceNumber();
        $invoice = Invoice::generate((array) $postData);

        if($invoice) {
            // Add Invoice Parcels
            //TODO validate waybill numbers
            InvoiceParcel::addParcels($postData->invoice_number, $postData->parcels);
            return $this->response->sendSuccess();
        } else {
            return $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_INVOICE);
        }
    }

    /**
     * Get paginated list of invoices
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getAllAction()
    {

    }
}