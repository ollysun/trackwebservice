<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNoteParcel extends EagerModel
{
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