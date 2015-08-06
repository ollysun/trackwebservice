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
    const INVALID_AMOUNT = 'Invalid amount(s) provided';
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
    const RECORD_DOES_NOT_EXIST = 'Record does not exist';
    const REGION_DOES_NOT_EXIST = 'Region does not exist';
    const CITY_DOES_NOT_EXIST = 'City does not exist';
    const STATE_DOES_NOT_EXIST = 'State does not exist';
    const REGION_EXISTS = 'Region already exists';
    const CITY_EXISTS = 'City already exists';
    const INVALID_ACTIVE_FG = 'Invalid active flag provided';

    const ZONE_NAME_EXISTS = 'A zone with this name already exists';
    const ZONE_CODE_EXISTS = 'A zone with this code already exists';
    const ZONE_DOES_NOT_EXIST = 'Zone does not exists';

    const ONFORWARDING_CHARGE_NAME_EXISTS = 'An onforwarding charge with this name already exists';
    const ONFORWARDING_CHARGE_CODE_EXISTS = 'An onforwarding charge with this code already exists';
    const ONFORWARDING_CHARGE_DOES_NOT_EXIST = 'Onforwarding charge does not exists';

    const NEGATIVE_WEIGHT = 'Negative weights not allowed';
    const INVALID_WEIGHT = 'Invalid weight(s) provided';
    const WEIGHT_RANGE_DOES_NOT_EXIST = 'Weight range does not exists';
    const BASE_WEIGHT_CHANGE = 'Base weight of 0.0 cannot be changed';
    const INCREMENT_WEIGHT_TOO_LARGE = 'The increment weight is larger than range';

    const BILLING_EXISTS = 'Weight range billing for zone already exists';
    const BILLING_NOT_EXISTS = 'Weight range billing for zone does not exists';

    const INVALID_VALUES = 'Invalid values provided';

    const INTERNAL_ERROR = 'An internal error has occurred';

    const ZONE_MATRIX_NOT_EXIST = 'Zone matrix mapping does not exist';
}