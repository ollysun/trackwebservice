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

        $creditNoteRequestValidation = new CreditNoteRequestValidation();
        if($creditNoteRequestValidation->validate($postData)) {
            return $this->response->sendError($creditNoteRequestValidation->getMessages());
        }

        $this->db->begin();

        // Generate credit notes
        $postData->credit_note_number = CreditNote::generateCreditNumber();
        $creditNote = CreditNote::add($postData);

        if($creditNote) {
            if(!$postData->parcels) {
                return $this->response->sendError(ResponseMessage::INVOICE_PARCEL_IS_REQUIRED_TO_CREATE_CREDIT_NOTE);
            }

            // Validate parcels
            if(!CreditNoteParcel::validateInvoiceParcels($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::ONE_OF_THE_PARCEL_DOES_NOT_EXIST);
            }

            if (!CreditNoteParcel::validateCreditNoteParcel($postData->parcels)) {
                return $this->response->sendError(ResponseMessage::CREDIT_NOTE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS);
            }
        }

        $this->db->rollback();

        return $this->response->sendSuccess($creditNote);
    }
}