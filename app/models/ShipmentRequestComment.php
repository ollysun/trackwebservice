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
        $this->setSource('shipment_request_comments');
    }

    /**
     * Adds a decline comment about a shipment request
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $shipmentRequestId
     * @param $comment
     * @param $type
     * @return bool
     */
    public static function add($shipmentRequestId, $comment, $type)
    {
        $shipmentRequestComment = new self();
        $shipmentRequestComment->shipment_request_id = $shipmentRequestId;
        $shipmentRequestComment->comment = $comment;
        $shipmentRequestComment->type = $type;

        return $shipmentRequestComment->save();
    }
}