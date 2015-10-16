<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
interface CorporateRequestStatusInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELED = 'canceled';
    const STATUS_DECLINED = 'declined';
}