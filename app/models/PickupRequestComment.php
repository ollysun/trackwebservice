<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class PickupRequestComment extends BaseModel implements CorporateRequestCommentTypeInterface
{
    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('pickup_request_comments');
    }

    /**
     * Adds a decline comment about a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $pickupRequestId
     * @param $comment
     * @return bool
     */
    public static function add($pickupRequestId, $comment, $type)
    {
        $pickupRequestComment = new self();
        $pickupRequestComment->pickup_request_id = $pickupRequestId;
        $pickupRequestComment->comment = $comment;
        $pickupRequestComment->type = $type;
        return $pickupRequestComment->save();
    }
}