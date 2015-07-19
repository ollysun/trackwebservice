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
    const PARCEL_NOT_EXISTING = 'Parcel not existing';
    const RELINK_NOT_POSSIBLE = 'EC not linked to HUB to be re-link';
    const CANNOT_MOVE_PARCEL = 'Parcel could not be moved';
    const PARCEL_NOT_FROM_ARRIVAL = 'Parcel not from arrivals';
    const PARCEL_NOT_IN_TRANSIT = 'Parcel not in transit';
    const PARCEL_NOT_IN_OFFICE = 'Parcel not in the office';
    const PARCEL_WRONG_DESTINATION = 'Parcel in wrong destination';
    const PARCEL_NOT_HEADING_TO_DESTINATION = 'Parcel not heading to destination';
    const PARCEL_CANNOT_BE_CLEARED = 'Parcel cannot be cleared';
    const PARCEL_HELD_BY_WRONG_OFFICIAL = 'Parcel held by wrong official';
    const PARCEL_NOT_FOR_SWEEPING = 'Parcel not for sweeping';
    const PARCEL_NOT_CLEARED_FOR_TRANSIT = 'Parcel not cleared for transit';
    const PARCEL_ALREADY_FOR_SWEEPER = 'Parcel already moved for sweeping';
    const PARCEL_ALREADY_IN_ARRIVAL = 'Parcel already in arrival';
    const PARCEL_ALREADY_IN_TRANSIT = 'Parcel already in transit';
    const PARCEL_ALREADY_FOR_DELIVERY = 'Parcel already for delivery';
}