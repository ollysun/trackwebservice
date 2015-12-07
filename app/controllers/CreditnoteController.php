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

        // Generate credit notes
        $postData->credit_note_number = CreditNote::generateCreditNumber();
        $creditNote = CreditNote::add($postData);

        if($creditNote) {

        }

        return $this->response->sendSuccess($creditNote);
    }
}