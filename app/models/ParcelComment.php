<?php

/**
 * Class ParcelComment
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class ParcelComment extends BaseModel
{

    const COMMENT_TYPE_RETURNED = 'returned';
    static $comment_types = [self::COMMENT_TYPE_RETURNED];

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('parcel_comments');
        $this->belongsTo('created_by', Admin::class, 'id', ['alias' => 'Creator']);
        $this->belongsTo('waybill_number', Parcel::class, 'waybill_number', ['alias' => 'Parcel']);
    }
}