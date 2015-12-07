<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditnoteController extends ControllerBase
{
    /**
     * Create Credit Note API
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();

        $creditNoteRequestValidation = new CreditNoteRequestValidation($postData);
        if(!$creditNoteRequestValidation->validate()) {
            return $this->response->sendError($creditNoteRequestValidation->getMessages());
        }

        $this->db->begin();

        // Generate credit notes
        $postData->credit_note_number = CreditNote::generateCreditNoteNumber();
        $creditNote = CreditNote::add($postData);

        if($creditNote) {
            // Validate parcels
            if(!$postData->parcels) {
                return $this->response->sendError(ResponseMessage::INVOICE_PARCEL_IS_REQUIRED_TO_CREATE_CREDIT_NOTE);
            }

            if(!CreditNoteParcel::validateInvoiceParcels($postData->invoice_number, $postData->parcels)) {
                return $this->response->sendError(ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST_OR_DOES_NOT_BELONG_TO_INVOICE);
            }

            if (!CreditNoteParcel::validateCreditNoteParcel($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::CREDIT_NOTE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS);
            }

            $this->db->commit();
            CreditNoteParcel::addParcels($postData->credit_note_number, $postData->parcels);

            return $this->response->sendSuccess($creditNote);
        }

        $this->db->rollback();
        $this->response->sendError(ResponseMessage::UNABLE_TO_CREATE_CREDIT_NOTE);
    }
}