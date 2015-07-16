<?php

/**
 * Created by PhpStorm.
 * User: adeyemi
 * Date: 5/1/15
 * Time: 6:30 PM
 */
class ResponseMessage
{
    const ERROR_REQUIRED_FIELDS = 'Required field(s) not sent';
    const INVALID_EMAIL = 'Invalid email provided';
    const EXISTING_EMAIL = 'Email provided already exists';
    const EXISTING_STAFF_ID = 'Staff Id provided already exists';
    const INVALID_CRED = 'Invalid credentials entered';
    const NO_RECORD_FOUND = 'No record found';
    const EC_NOT_LINKED_TO_HUB = 'This EC is not linked to a Hub';
    const SWEEPER_ONLY_TO_HUB = 'A sweeper can only attached to a hub';
    const BRANCH_NOT_EXISTING = 'Branch does not exist';
    const INVALID_PACKAGE_COUNT = 'Invalid package count provided';
    const INVALID_AMOUNT = 'Invalid amounts provided';
    const INVALID_PAYMENT_TYPE = 'Invalid payment type provided';
    const INVALID_STATUS = 'Invalid status provided';
    const NO_HUB_PROVIDED = 'No HUB provided';
    const INVALID_HUB_PROVIDED = 'Invalid HUB provided';
    const INVALID_EC_PROVIDED = 'Invalid EC provided';
    const HUB_NOT_EXISTING = 'HUB not existing';
    const EC_NOT_EXISTING = 'EC not existing';
    const RELINK_NOT_POSSIBLE = 'EC not linked to HUB to be re-link';
}