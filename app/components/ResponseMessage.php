<?php

/**
 * Created by PhpStorm.
 * User: adeyemi
 * Date: 5/1/15
 * Time: 6:30 PM
 */
class ResponseMessage
{
    const PASSWORD_TOO_SMALL = 'Password is lesser than 6 characters';
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
    const PARCEL_ALREADY_CANCELLED = 'Parcel already cancelled';
    const PARCEL_CANNOT_BE_CANCELLED = 'Parcel cannot be cancelled';
    const RECORD_DOES_NOT_EXIST = 'Record does not exist';
    const REGION_DOES_NOT_EXIST = 'Region does not exist';
    const CITY_DOES_NOT_EXIST = 'City does not exist';
    const STATE_DOES_NOT_EXIST = 'State does not exist';
    const REGION_EXISTS = 'Region already exists';
    const CITY_EXISTS = 'City already exists';
    const INVALID_ACTIVE_FG = 'Invalid active flag provided';
    const PARCEL_ALREADY_FOR_BEING_DELIVERED = 'Parcel already being delivered';
    const PARCEL_ALREADY_DELIVERED = 'Parcel already delivered';
    const PARCEL_NOT_FOR_DELIVERY = 'Parcel not for delivery';
    const INVALID_SWEEPER_OR_DISPATCHER = 'Invalid sweeper or dispatcher provided';
    const INVALID_OFFICER = 'Invalid officer provided';
    const PARCEL_NOT_IN_OFFICER_BRANCH = 'Parcel not in officer\'s branch';
    const INVALID_BRANCH = 'Invalid Branch Provided';

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

    const PARCEL_ON_TELLER_ALREADY = 'Parcel is already on a teller';
    const TELLER_ALREADY_USED = 'Teller has been uploaded already';

    const INVALID_VALUES = 'Invalid values provided';

    const INTERNAL_ERROR = 'An internal error has occurred';

    const ZONE_MATRIX_NOT_EXIST = 'Zone matrix mapping does not exist';

    const NO_PARCEL_TO_BAG = 'No parcel to bag';
    const NO_PARCEL_TO_REMOVE_FROM_BAG = 'No parcel to remove from bag';
    const PARCEL_NOT_BE_BAGGED = 'Parcel cannot be bagged';
    const PARCEL_ALREADY_BAGGED = 'Parcel is already bagged';
    const PARCEL_NOT_GOING_TO_BAG_LOC = 'Parcel not heading to bag\'s destination';
    const BAG_DOES_NOT_EXIST = 'Bag does not exist';
    const BAG_IN_MANIFEST = 'Bag is already in a manifest';
    const PARCEL_NOT_IN_BAG = 'Parcel is not in bag';
    const COULD_NOT_REMOVE_FROM_BAG = 'Unable to remove parcel(s) from bag';

    const CAN_ONLY_DELIVER_FROM_EC = 'Must be login to an EC to set parcels for delivery';

    const MANIFEST_NOT_RECEIVABLE = 'Manifest cannot be receive at your branch';
    const MANIFEST_PARCEL_DIRECTION_MISMATCH = 'Parcel\'s origin and destination is not the same as manifest';

    const STAFF_DOES_NOT_EXIST = 'Staff does not exist';
    const UNABLE_TO_CREATE_ROUTE = 'Unable to create route';
    const UNABLE_TO_EDIT_ROUTE = 'Unable to edit route';
    const INVALID_ROUTE = 'Route does not exist';
    const ACCOUNT_DOES_NOT_EXIST = 'Account does not exist';
    const UNABLE_TO_RESET_PASSWORD = 'Unable to reset password';
    const INVALID_TOKEN = 'Invalid password reset token';

    const COMPANY_EXISTING = 'Another company with this same name exists';
    const UNABLE_TO_CREATE_COMPANY = 'Could not create company';
    const UNABLE_TO_CREATE_COMPANY_PRIMARY_CONTACT = 'Could not create company primary contact';
    const UNABLE_TO_CREATE_COMPANY_SECONDARY_CONTACT = 'Could not create company secondary contact';
    const UNABLE_TO_LINK_CONTACTS_TO_COMPANY = 'Unable to link secondary and primary contact to company';
    const INVALID_CITY_SUPPLIED = 'Invalid city supplied';
    const INVALID_RELATIONS_OFFICER_ID = 'Invalid relations officer id supplied';
    const PRIMARY_CONTACT_EXISTS = 'Primary contact already exists';
    const SECONDARY_CONTACT_EXISTS = 'Secondary contact already exists';
    const INVALID_COMPANY_ID_SUPPLIED = 'Invalid company id supplied';
    const INVALID_RECEIVER_CITY_SUPPLIED = 'Invalid receiver city supplied';
    const INVALID_RECEIVER_STATE_SUPPLIED = 'Invalid receiver state supplied';
    const COULD_NOT_CREATE_REQUEST = 'Could not create request';

    const INVALID_REQUEST_TYPE = 'Invalid request type';
    const PARCEL_NOT_ACCESSIBLE = 'Parcel not accessible for this action';
    const PARCEL_CANNOT_CHANGE_RETURN_FLAG = 'The return flag of the parcel could not be altered';

    const PARCEL_ALREADY_RETURNED = 'Parcel already returned';
    const PARCEL_NOT_FOR_RETURN = 'Parcel not for return';
    const CANNOT_RETURN_PARCEL = 'Parcel not returned';

    const UNABLE_TO_CANCEL_REQUEST = 'Unable to cancel request';
    const UNABLE_TO_DECLINE_REQUEST = 'Unable to decline request';
}