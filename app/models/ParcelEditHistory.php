<?php

/**
 * @author Babatunde Otaru <tunde@cottacush.com>
 */
class ParcelEditHistory extends BaseModel
{
    public function initialize()
    {
        $this->setSource('parcel_edit_history');
    }
}