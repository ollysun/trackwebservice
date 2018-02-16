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

    public static function add($data)
    {
        $status = new StatusHistory();
        $status->setComment($data['comment']);
        $status->setWaybillNumber($data['waybill_number']);
        $status->setExtraNote($data['extra_note']);
        $status->setCreatedBy($data['created_by']);
        $status->save();
        parent::add($data);
    }
}