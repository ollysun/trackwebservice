<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class ShipmentRequestComment extends BaseModel implements CorporateRequestCommentTypeInterface
{
    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('shipment_requests_comments');
    }

    /**
     * Adds a decline comment about a shipment request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $shipmentRequestId
     * @param $comment
     * @return bool
     */
    public static function addDeclineComment($shipmentRequestId, $comment)
    {
        $shipmentRequestComment = new self();
        $shipmentRequestComment->shipment_request_id = $shipmentRequestId;
        $shipmentRequestComment->comment = $comment;
        $shipmentRequestComment->type = self::COMMENT_TYPE_DECLINED;

        return $shipmentRequestComment->save();
    }
}