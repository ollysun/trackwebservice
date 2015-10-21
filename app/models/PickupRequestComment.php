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
        $this->setSource('pickup_requests_comments');
    }

    /**
     * Adds a decline comment about a pickup request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $pickupRequestId
     * @param $comment
     * @return bool
     */
    public static function addDeclineComment($pickupRequestId, $comment)
    {
        $pickupRequestComment = new self();
        $pickupRequestComment->pickup_request_id = $pickupRequestId;
        $pickupRequestComment->comment = $comment;
        $pickupRequestComment->type = self::COMMENT_TYPE_DECLINED;

        return $pickupRequestComment->save();
    }
}