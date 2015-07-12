<?php


class ParcelController extends ControllerBase {
    public function addAction(){
        //todo: must be tied to an EC Officer only
        /**
         * Sample data expected
         * =====================
        $sender = [
        'firstname' => 'ibrahim',
        'lastname' => 'mutiu',
        'phone' => '2345072345566',
        'email' => null
        ];

        $receiver = [
        'firstname' => 'biliki',
        'lastname' => 'ali',
        'phone' => '2345072341212',
        'email' => 'biliki.ali@gmail.com'
        ];

        $sender_address = [
        'id' => null,
        'street_address1' => '2, Osborne way',
        'street_address2' => 'Ikoyi, Lagos State',
        'city' => 'Ikoyi',
        'state_id' => '25',
        'country_id' => '1'
        ];

        $receiver_address = [
        'id' => null,
        'street_address1' => '23, Apple Cresent',
        'street_address2' => 'Mayitama, Abuja',
        'city' => 'Ikoyi',
        'state_id' => '15',
        'country_id' => '1'
        ];

        $bank_account = [
        'id' => null,
        'bank_id' => 12,
        'account_name' => 'Ibrahim ali',
        'account_no'=> '324156424',
        'sort_code'=> '1222432'
        ];

        $parcel = [
        'parcel_type' => 1,
        'weight' => 123.25,
        'amount_due' => 2000,
        'cash_on_delivery' => 1,
        'cash_on_delivery_amount' => 3000,
        'delivery_type' => 2,
        'payment_type' => 2,
        'shipping_type' => 1,
        'package_value' => 2000,
        'no_of_package' => 3,
        'other_info' => 'some other info',
        'cash_amount' => 500,
        'pos_amount' =>  1500,
        ];

        $to_hub = 1; // 0 if the delivery is within the EC, 1 if to be sent to HUB
         */

        $this->auth->allowOnly([Role::OFFICER]);
        $payload = $this->request->getJsonRawBody(true);

        $sender = $payload['sender'];
        $sender_address = $payload['sender_address'];
        $receiver = $payload['receiver'];
        $receiver_address = $payload['receiver_address'];
        $bank_account = $payload['bank_account'];
        $parcel = $payload['parcel'];
        $to_hub = $payload['to_hub'];

        if (in_array(null, array($parcel, $sender, $sender_address, $receiver, $receiver_address, $bank_account)) or $to_hub === null){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $auth_data = $this->auth->getData();

        //Ensuring the officer is an EC officer
        if ($auth_data['branch']['branch_type'] != BranchType::EC){
            return $this->response->sendAccessDenied();
        }

        //determining destination branch
        $to_branch_id = $auth_data['branch']['id'];
        if ($to_hub > 0){
            $to_branch = Branch::getParentById($auth_data['branch']['id']);
            if ($to_branch == null){
                return $this->response->sendError(ResponseMessage::EC_NOT_LINKED_TO_HUB);
            }

            $to_branch_id = $to_branch->getId();
        }

        //parcel no_of_package validation
        $parcel['no_of_package'] = intval($parcel['no_of_package']);
        if ($parcel['no_of_package'] < 1){
            return $this->response->sendError(ResponseMessage::INVALID_PACKAGE_COUNT);
        }

        //parcel cash_amount and pos_amount sanitation
        switch($parcel['payment_type']){
            case PaymentType::CASH:
                $parcel['cash_amount'] = $parcel['amount_due'];
                $parcel['pos_amount'] = 0.0;
                break;
            case PaymentType::POS:
                $parcel['cash_amount'] = 0.0;
                $parcel['pos_amount'] = $parcel['amount_due'];
                break;
            case PaymentType::CASH_AND_POS:
                if (!isset($parcel['cash_amount']) or !isset($parcel['pos_amount'])){
                    return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
                }else if ((bccomp($parcel['cash_amount'] + $parcel['pos_amount'], $parcel['amount_due'], 2) != 0)){
                    return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
                }
                break;
            default:
                return $this->response->sendError(ResponseMessage::INVALID_PAYMENT_TYPE);
        }


        $parcel_obj = new Parcel();
        $check = $parcel_obj->saveForm($auth_data['branch']['id'], $sender, $sender_address, $receiver, $receiver_address,
            $bank_account, $parcel, $to_branch_id, $this->auth->getClientId());
        if ($check){
            return $this->response->sendSuccess(['id' => $parcel_obj->getId(), 'waybill_number' => $parcel_obj->getWaybillNumber()]);
        }
        return $this->response->sendError();
    }

    public function getOneAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $id = $this->request->getQuery('id');

        if (is_null($id)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $parcel = Parcel::fetchOne($id);
        if ($parcel != false){
            return $this->response->sendSuccess($parcel);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    private function getFilterParams(){
        $to_branch_id = $this->request->getQuery('to_branch_id');
        $from_branch_id = $this->request->getQuery('from_branch_id');
        $parcel_type = $this->request->getQuery('parcel_type');
        $sender_id = $this->request->getQuery('sender_id');
        $sender_address_id = $this->request->getQuery('sender_address_id');
        $receiver_id = $this->request->getQuery('receiver_id');
        $receiver_address_id = $this->request->getQuery('receiver_address_id');
        $status = $this->request->getQuery('status');
        $min_weight = $this->request->getQuery('min_weight');
        $max_weight = $this->request->getQuery('max_weight');
        $min_amount_due = $this->request->getQuery('min_amount_due');
        $max_amount_due = $this->request->getQuery('max_amount_due');
        $cash_on_delivery = $this->request->getQuery('cash_on_delivery');
        $min_delivery_amount = $this->request->getQuery('min_delivery_amount');
        $max_delivery_amount = $this->request->getQuery('max_delivery_amount');
        $delivery_type = $this->request->getQuery('delivery_type');
        $payment_type = $this->request->getQuery('payment_type');
        $shipping_type = $this->request->getQuery('shipping_type');
        $min_cash_amount = $this->request->getQuery('min_cash_amount');
        $max_cash_amount = $this->request->getQuery('max_cash_amount');
        $min_pos_amount = $this->request->getQuery('min_pos_amount');
        $max_pos_amount = $this->request->getQuery('max_pos_amount');
        $start_created_date = $this->request->getQuery('start_created_date');
        $end_created_date = $this->request->getQuery('end_created_date');
        $start_modified_date = $this->request->getQuery('start_modified_date');
        $end_modified_date = $this->request->getQuery('end_modified_date');
        $waybill_number = $this->request->getQuery('waybill_number');

        $filter_by = [];
        if (!is_null($to_branch_id)){ $filter_by['to_branch_id'] = $to_branch_id; }
        if (!is_null($from_branch_id)){ $filter_by['from_branch_id'] = $from_branch_id; }
        if (!is_null($parcel_type)){ $filter_by['parcel_type'] = $parcel_type; }
        if (!is_null($sender_id)){ $filter_by['sender_id'] = $sender_id; }
        if (!is_null($sender_address_id)){ $filter_by['sender_address_id'] = $sender_address_id; }
        if (!is_null($receiver_id)){ $filter_by['receiver_id'] = $receiver_id; }
        if (!is_null($receiver_address_id)){ $filter_by['receiver_address_id'] = $receiver_address_id; }
        if (!is_null($status)){ $filter_by['status'] = $status; }
        if (!is_null($min_weight)){ $filter_by['min_weight'] = $min_weight; }
        if (!is_null($max_weight)){ $filter_by['max_weight'] = $max_weight; }
        if (!is_null($min_amount_due)){ $filter_by['min_amount_due'] = $min_amount_due; }
        if (!is_null($max_amount_due)){ $filter_by['max_amount_due'] = $max_amount_due; }
        if (!is_null($cash_on_delivery)){ $filter_by['cash_on_delivery'] = $cash_on_delivery; }
        if (!is_null($min_delivery_amount)){ $filter_by['min_delivery_amount'] = $min_delivery_amount; }
        if (!is_null($max_delivery_amount)){ $filter_by['max_delivery_amount'] = $max_delivery_amount; }
        if (!is_null($delivery_type)){ $filter_by['delivery_type'] = $delivery_type; }
        if (!is_null($payment_type)){ $filter_by['payment_type'] = $payment_type; }
        if (!is_null($shipping_type)){ $filter_by['shipping_type'] = $shipping_type; }
        if (!is_null($min_cash_amount)){ $filter_by['min_cash_amount'] = $min_cash_amount; }
        if (!is_null($max_cash_amount)){ $filter_by['max_cash_amount'] = $max_cash_amount; }
        if (!is_null($min_pos_amount)){ $filter_by['min_pos_amount'] = $min_pos_amount; }
        if (!is_null($max_pos_amount)){ $filter_by['max_pos_amount'] = $max_pos_amount; }
        if (!is_null($start_created_date)){ $filter_by['start_created_date'] = $start_created_date; }
        if (!is_null($end_created_date)){ $filter_by['end_created_date'] = $end_created_date; }
        if (!is_null($start_modified_date)){ $filter_by['start_modified_date'] = $start_modified_date; }
        if (!is_null($end_modified_date)){ $filter_by['end_modified_date'] = $end_modified_date; }
        if (!is_null($waybill_number)){ $filter_by['waybill_number'] = $waybill_number; }

        return $filter_by;
    }

    public function getAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $with_to_branch = $this->request->getQuery('with_to_branch');
        $with_from_branch = $this->request->getQuery('with_from_branch');
        $with_sender = $this->request->getQuery('with_sender');
        $with_sender_address = $this->request->getQuery('with_sender_address');
        $with_receiver = $this->request->getQuery('with_receiver');
        $with_receiver_address = $this->request->getQuery('with_receiver_address');

        $filter_by = $this->getFilterParams();

        $fetch_with = [];
        if (!is_null($with_to_branch)){ $fetch_with['with_to_branch'] = true; }
        if (!is_null($with_from_branch)){ $fetch_with['with_from_branch'] = true; }
        if (!is_null($with_sender)){ $fetch_with['with_sender'] = true; }
        if (!is_null($with_receiver)){ $fetch_with['with_receiver'] = true; }
        if (!is_null($with_sender_address)){ $fetch_with['with_sender_address'] = true; }
        if (!is_null($with_receiver_address)){ $fetch_with['with_receiver_address'] = true; }

        return $this->response->sendSuccess(Parcel::fetchAll($offset, $count, $filter_by, $fetch_with));
    }

    public function countAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $filter_by = $this->getFilterParams();

        $count = Parcel::parcelCount($filter_by);
        if ($count === null){
            return $this->response->sendError();
        }
        return $this->response->sendSuccess($count);
    }

    public function moveAction(){

    }
} 