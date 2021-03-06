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
    const SWEEPER_ONLY_TO_HUB_OR_EC = 'A sweeper can only attached to a hub or Express Centre';
    const BRANCH_NOT_EXISTING = 'Branch does not exist';
    const INVALID_PACKAGE_COUNT = 'Invalid package count provided';
    const INVALID_AMOUNT = 'Invalid amount(s) provided';
    const INVALID_PERCENTAGE = 'Invalid percentage(s) provided';
    const INVALID_PAYMENT_TYPE = 'Invalid payment type provided';
    const INVALID_STATUS = 'Invalid status provided';
    const NO_HUB_PROVIDED = 'No HUB provided';
    const INVALID_HUB_PROVIDED = 'Invalid HUB provided';
    const INVALID_EC_PROVIDED = 'Invalid EC provided';
    const HUB_NOT_EXISTING = 'HUB not existing';
    const EC_NOT_EXISTING = 'EC not existing';
    const PARCEL_NOT_EXISTING = 'Parcel not existing';
    const PARCEL_REF_NOT_EXISTING = 'Parcel with reference no not existing';
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
    const PARCEL_RETURNED = 'Parcel return successfully';
    const RECORD_DOES_NOT_EXIST = 'Record does not exist';
    const REGION_DOES_NOT_EXIST = 'Region does not exist';
    const CITY_DOES_NOT_EXIST = 'City does not exist';
    const STATE_DOES_NOT_EXIST = 'State does not exist';
    const REGION_EXISTS = 'Region already exists';
    const CITY_EXISTS = 'City already exists';
    const INVALID_ACTIVE_FG = 'Invalid active flag provided';
    const PARCEL_ALREADY_FOR_BEING_DELIVERED = 'Parcel already being delivered';
    const PARCEL_ALREADY_DELIVERED = 'Parcel already delivered';
    const PARCEL_NOT_DELIVERED = 'Parcel not delivered';
    const PARCEL_NOT_FOR_DELIVERY = 'Parcel not for delivery';
    const INVALID_SWEEPER_OR_DISPATCHER = 'Invalid sweeper or dispatcher provided';
    const INVALID_OFFICER = 'Invalid officer provided';
    const PARCEL_NOT_IN_OFFICER_BRANCH = 'Parcel not in officer\'s branch';
    const INVALID_BRANCH = 'Invalid Branch Provided';
    const INVALID_FREIGHT_INCLUSION = 'Freight cost can only be added for shipment with Cash on Delivery with Deferred payment';
    const INVALID_QTY_METRICS = 'Invalid quantity metric specified';

    const ZONE_NAME_EXISTS = 'A zone with this name already exists';
    const ZONE_CODE_EXISTS = 'A zone with this code already exists';
    const ZONE_DOES_NOT_EXIST = 'Zone does not exists';

    const ONFORWARDING_CHARGE_NAME_EXISTS = 'An onforwarding charge with this name already exists';
    const ONFORWARDING_CHARGE_CODE_EXISTS = 'An onforwarding charge with this code already exists';
    const ONFORWARDING_CHARGE_DOES_NOT_EXIST = 'Onforwarding charge does not exists';

    const NEGATIVE_WEIGHT = 'Negative weights not allowed';
    const INVALID_WEIGHT = 'Invalid weight(s) provided';
    const WEIGHT_RANGE_DOES_NOT_EXIST = 'Weight range does not exists';
    const WEIGHT_RANGE_STILL_HAS_EXISTING_BILLING = 'Weight range still has existing billing';
    const UNABLE_TO_DELETE_WEIGHT_RANGE = 'Unable to delete weight range';
    const BASE_WEIGHT_CHANGE = 'Base weight of 0.0 cannot be changed';
    const INCREMENT_WEIGHT_TOO_LARGE = 'The increment weight is larger than range';

    const BILLING_EXISTS = 'Weight range billing for zone already exists';
    const BILLING_NOT_EXISTS = 'Weight range billing for zone does not exists';

    const PARCEL_ON_TELLER_ALREADY = 'Parcel is already on a teller';
    const TELLER_ALREADY_USED = 'Teller has been uploaded already';

    const INVALID_VALUES = 'Invalid values provided';

    const INTERNAL_ERROR = 'An internal error has occurred';

    const ZONE_MATRIX_NOT_EXIST = 'Zone matrix mapping does not exist';

    const TRANSIT_TIME_NOT_EXIST = 'Transit time mapping does not exist';

    const SEAL_ID_IN_USE = "The seal ID you entered is already in use";
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
    const WRONG_ROUTE = 'The route does not belong to this branch';

    const COMPANY_EXISTING = 'Another company with this same NAME exists';
    const UNABLE_TO_CREATE_COMPANY = 'Could not create company';
    const UNABLE_TO_CREATE_COMPANY_PRIMARY_CONTACT = 'Could not create company primary contact';
    const UNABLE_TO_CREATE_COMPANY_SECONDARY_CONTACT = 'Could not create company secondary contact';
    const UNABLE_TO_LINK_CONTACTS_TO_COMPANY = 'Unable to link secondary and primary contact to company';
    const INVALID_CITY_SUPPLIED = 'Invalid city supplied';
    const INVALID_RELATIONS_OFFICER_ID = 'Invalid relations officer id supplied';
    const PRIMARY_CONTACT_EXISTS = 'Primary contact already exists';
    const SECONDARY_CONTACT_EXISTS = 'Secondary contact already exists';
    const INVALID_COMPANY_ID_SUPPLIED = 'Invalid company id supplied';
    const UNABLE_TO_CHANGE_COMPANY_STATUS = 'Could not update the company\'s status';
    const INVALID_RECEIVER_CITY_SUPPLIED = 'Invalid receiver city supplied';
    const INVALID_RECEIVER_STATE_SUPPLIED = 'Invalid receiver state supplied';
    const COULD_NOT_CREATE_REQUEST = 'Could not create request';
    const COMPANY_USER_HAS_NO_EMAIL = 'Company user has no email';

    const INVALID_REQUEST_TYPE = 'Invalid request type';
    const PARCEL_NOT_ACCESSIBLE = 'Parcel not accessible for this action';
    const PARCEL_CANNOT_CHANGE_RETURN_FLAG = 'The return flag of the parcel could not be altered';

    const PARCEL_ALREADY_RETURNED = 'Parcel already returned';
    const PARCEL_NOT_FOR_RETURN = 'Parcel not for return';
    const CANNOT_RETURN_PARCEL = 'Parcel not returned';
    const PARCEL_CANNOT_BE_RECEIVED = 'Parcel cannot be received from dispatcher';

    const UNABLE_TO_CANCEL_REQUEST = 'Unable to cancel request';
    const UNABLE_TO_DECLINE_REQUEST = 'Unable to decline request';
    const UNABLE_TO_EDIT_COMPANY = 'Unable to edit company';
    const COMPANY_USER_ALREADY_EXISTS = 'Company user already exists';
    const UNABLE_TO_UPDATE_COMPANY_USER = 'Unable to update company user';
    const UNABLE_TO_LINK_EC_TO_COMPANY = 'Unable to link EC to company';
    const UNABLE_TO_RELINK_EC_TO_COMPANY = 'Unable to edit EC link to company';

    const INVALID_TYPE = 'Invalid type provided';
    const INVALID_BILLING_PLAN = 'Invalid billing plan provided';
    const ANOTHER_HAS_SAME_NAME = 'Another has same name';
    const BILLING_PLAN_DOES_NOT_EXIST = 'BIlling plan does not exist';
    const BILLING_PLAN_NOT_SAVED = 'Could not save billing plan';
    const BILLING_PLAN_NOT_CLONED = 'Could not clone billing Plan';
    const COMPANY_NOT_LINKED_TO_PLAN = 'Company not linked to billing plan';

    const ONFORWARDING_CITY_EXISTS = 'City is already linked to onforwarding charge';
    const ONFORWARDING_CITY_NOT_EXISTS = 'City is not linked to onforwarding charge';
    const ONFORWARDING_CITY_NOT_SAVED = 'City could not linked to onforwarding charge';

    const CALC_BILLING_WEIGHT = 'Could not calculate the weight billing for the parcel';
    const CALC_BILLING_ONFORWARDING = 'Could not calculate the onforwarding billing for the parcel';
    const UNABLE_TO_CREATE_INVOICE = 'Unable to create invoice';
    const ONE_OF_THE_PARCEL_DOES_NOT_EXIST = 'One of the parcel(s) does not exists';
    const INVOICE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS = 'Invoice already exists for one of the parcels';
    const CREDIT_NOTE_ALREADY_EXISTS_FOR_ONE_OF_THE_PARCELS = 'Credit note already exists for one of the parcels';
    const INVOICE_PARCEL_IS_REQUIRED_TO_CREATE_CREDIT_NOTE = 'At least one invoice parcel is needed to create a credit note';
    const UNABLE_TO_CREATE_CREDIT_NOTE = 'Unable to create credit note';
    const ONE_OF_THE_PARCEL_DOES_NOT_EXIST_OR_DOES_NOT_BELONG_TO_INVOICE = 'One of the parcel(s) does not exists or does not belong to the invoice';
    const INVOICE_DOES_NOT_EXISTS = 'Invoice does not exists';
    const ZONE_MAPPING_ORIGIN_DESTINATION_NOT_EXIST = 'Zone mapping between origin and destination does not exist. Please contact administrator';
    const WEIGHT_BILLING_DOES_NOT_EXIST_FOR_ZONE = 'Billing not set for weight range in selected zone. Please contact administrator';
    const INVALID_WEIGHT_BILLING_PLAN = 'Invalid weight billing plan supplied';
    const INVALID_ONFORWARDING_BILLING_PLAN = 'Invalid onforwarding billing plan supplied';
    const WEIGHT_EXCEEDS_SET_LIMIT = 'Parcel weight exceeds set limit. Please contact administrator';
    const AMOUNT_EXCEEDS_CREDIT_BALANCE = 'Transaction Amount Exceeds Credit Balance';
}