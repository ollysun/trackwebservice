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
            if (!InvoiceParcel::validateParcels($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST);
            }

            if (!InvoiceParcel::validateInvoiceParcel($postData->parcels)) {
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
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $with_total_count = $this->request->getQuery('with_total_count', null, null);

        $fetch_with = Util::filterArrayKeysWithPattern('/\b^with_.+\b/', $this->request->getQuery());
        $filter_by = $this->request->getQuery();

        $invoices = Invoice::fetchAll($offset, $count, $fetch_with, $filter_by);

        if (!empty($with_total_count)) {
            $data = [
                'total_count' => Invoice::getTotalCount($filter_by),
                'invoices' => $invoices
            ];
        } else {
            $data = $invoices;
        }

        return $this->response->sendSuccess($data);
    }
}