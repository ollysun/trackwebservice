<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNoteController extends ControllerBase
{
    /**
     * Create Credit Note API
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function addAction()
    {
        $this->auth->allowOnly([Role::ADMIN]);
        $postData = $this->request->getJsonRawBody();


    }
}