<?php


class ParcelController extends ControllerBase {
    public function addAction(){
        //todo: must be tied to an EC Officer only
        $this->auth->allowOnly([Role::OFFICER]);
        $payload = $this->request->getJsonRawBody(true);
//        $payload = '{
//    "sender": {
//        "firstname": "Rotimo",
//        "lastname": "Akintewe",
//        "phone": "+2348033438870",
//        "email": "akintewe.rotimi@gmail.com"
//    },
//    "receiver": {
//        "firstname": "Dapo",
//        "lastname": "Olotu",
//        "phone": "09023454321",
//        "email": "dapo.olotu@gmail.com"
//    },
//    "sender_address": {
//        "id": null,
//        "street1": "3 Cuttacosh Road, Abule Egba.",
//        "street2": "",
//        "city": "Lagos",
//        "state_id": "1",
//        "country_id": "1"
//    },
//    "receiver_address": {
//        "id": null,
//        "street1": "9, Ojo Street, Akoka",
//        "street2": "",
//        "city": "Lagos",
//        "state_id": "1",
//        "country_id": "1"
//    },
//    "parcel": {
//        "parcel_type": "1",
//        "no_of_package": "23",
//        "weight": "176",
//        "parcel_value": "23000",
//        "amount_due": "23000",
//        "cash_on_delivery": 1,
//        "cash_on_delivery_amount": "120000",
//        "delivery_type": "2",
//        "payment_type": "2",
//        "shipping_type": "1",
//        "other_info": "This is the other information needed",
//        "cash_amount": null,
//        "pos_amount": null
//    },
//    "is_corporate_lead": 0,
//    "to_hub": 1
//}';
//        $payload = json_decode($payload, true);
        $sender = (isset($payload['sender'])) ? $payload['sender'] : null;
        $sender_address = (isset($payload['sender_address'])) ? $payload['sender_address'] : null;
        $receiver = (isset($payload['receiver'])) ? $payload['receiver'] : null;
        $receiver_address = (isset($payload['receiver_address'])) ? $payload['receiver_address'] : null;
        $bank_account = (isset($payload['bank_account'])) ? $payload['bank_account'] : null;
        $parcel = (isset($payload['parcel'])) ? $payload['parcel'] : null;
        $to_hub = (isset($payload['to_hub'])) ? $payload['to_hub'] : null;
        $is_corporate_lead = (isset($payload['is_corporate_lead'])) ? $payload['is_corporate_lead'] : null;

        if (in_array(null, array($parcel, $sender, $sender_address, $receiver, $receiver_address)) or $to_hub === null){
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
            if ($is_corporate_lead == 1){
                EmailMessage::send(
                    EmailMessage::CORPORATE_LEAD,
                    [
                        'firstname' => (isset($sender['firstname'])) ? ucfirst(strtolower($sender['firstname'])) : '',
                        'lastname' => (isset($sender['lastname'])) ? ucfirst(strtolower($sender['lastname'])) : '',
                        'street1' => (isset($sender_address['street1'])) ? $sender_address['street1'] : '',
                        'street2' => (isset($sender_address['street2'])) ? $sender_address['street2'] : '',
                        'city' => (isset($sender_address['city'])) ? ucfirst(strtolower($sender_address['city'])) : '',
                        'state' => (isset($sender_address['state'])) ? $sender_address['state'] : '',
                        'country' => (isset($sender_address['country'])) ? $sender_address['country'] : '',
                        'email' => (isset($sender['email'])) ? $sender['email'] : '',
                        'phone' => (isset($sender['phone'])) ? $sender['phone'] : ''
                    ],
                    'Courier Plus [' . strtoupper($auth_data['branch']['name']) . ']'
                );
            }
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
        $held_by_staff_id = $this->request->getQuery('held_by_staff_id');
        $held_by_id = $this->request->getQuery('held_by_id');
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
        if (!is_null($held_by_staff_id)){ $filter_by['held_by_staff_id'] = $held_by_staff_id; }
        if (!is_null($held_by_id)){ $filter_by['held_by_id'] = $held_by_id; }
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
        $with_holder = $this->request->getQuery('with_holder');

        $with_total_count = $this->request->getQuery('with_total_count');
        $send_all = $this->request->getQuery('send_all');

        $filter_by = $this->getFilterParams();

        if (!is_null($send_all)){ $filter_by['send_all'] = true; }

        $fetch_with = [];
        if (!is_null($with_to_branch)){ $fetch_with['with_to_branch'] = true; }
        if (!is_null($with_from_branch)){ $fetch_with['with_from_branch'] = true; }
        if (!is_null($with_sender)){ $fetch_with['with_sender'] = true; }
        if (!is_null($with_receiver)){ $fetch_with['with_receiver'] = true; }
        if (!is_null($with_sender_address)){ $fetch_with['with_sender_address'] = true; }
        if (!is_null($with_receiver_address)){ $fetch_with['with_receiver_address'] = true; }
        if (!is_null($with_holder)){ $fetch_with['with_holder'] = true; }

        $parcels = Parcel::fetchAll($offset, $count, $filter_by, $fetch_with);
        $result = [];
        if ($with_total_count != null){
            $count = Parcel::parcelCount($filter_by);
            $result = [
                'total_count' => $count,
                'parcels' => $parcels
            ];
        }else{
            $result = $parcels;
        }

        return $this->response->sendSuccess($result);
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

    private function sanitizeWaybillNumbers($waybill_numbers){
        $waybill_number_arr = explode(',', $waybill_numbers);

        $clean_arr = [];
        foreach ($waybill_number_arr as $number){
            $clean_arr[trim(strtoupper($number))] = true;
        }

        return array_keys($clean_arr);
    }

    public function moveToForSweeperAction(){
        $this->auth->allowOnly([Role::OFFICER]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $to_branch_id = $this->request->getPost('to_branch_id');

        if (in_array(null, [$waybill_numbers, $to_branch_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number){
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_FOR_SWEEPER){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_FOR_SWEEPER;
                continue;
            } else if ($parcel->getStatus() != Status::PARCEL_ARRIVAL){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FROM_ARRIVAL;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch']['id']){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_OFFICE;
                continue;
            }

            $check = $parcel->changeDestination(Status::PARCEL_FOR_SWEEPER, $to_branch_id, $this->auth->getClientId(), ParcelHistory::MSG_FOR_SWEEPER);
            if (!$check){
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    public function moveToArrivalAction(){
        $this->auth->allowOnly([Role::OFFICER]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $held_by_id = $this->request->getPost('held_by_id');

        if (in_array(null, [$waybill_numbers, $held_by_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number){
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_ARRIVAL){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_IN_ARRIVAL;
                continue;
            } else if ($parcel->getStatus() != Status::PARCEL_IN_TRANSIT){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_TRANSIT;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch']['id']){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_WRONG_DESTINATION;
                continue;
            }

            //checking if the parcel is held by the correct person
            $held_parcel_record = HeldParcel::fetchUncleared($parcel->getId(), $held_by_id);
            if ($held_parcel_record == false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_HELD_BY_WRONG_OFFICIAL;
                continue;
            }

            $check = $parcel->checkIn($held_parcel_record, $this->auth->getClientId());
            if (!$check){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_CANNOT_BE_CLEARED;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    public function moveToInTransitAction(){
        $this->auth->allowOnly([Role::SWEEPER, Role::OFFICER]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $to_branch_id = $this->request->getPost('to_branch_id');
        $held_by_id = ($this->auth->getUserType() == Role::SWEEPER) ? $this->auth->getClientId() : $this->request->getPost('held_by_id');
        $admin_id = ($this->auth->getUserType() == Role::OFFICER) ? $this->auth->getClientId() : $this->request->getPost('admin_id');

        if (in_array(null, [$waybill_numbers, $to_branch_id, $held_by_id, $admin_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number){
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_IN_TRANSIT){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_IN_TRANSIT;
                continue;
            } else if ($parcel->getStatus() != Status::PARCEL_FOR_SWEEPER){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FOR_SWEEPING;
                continue;
            } else if ($parcel->getToBranchId() != $to_branch_id){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_HEADING_TO_DESTINATION;
                continue;
            } else if (!HeldParcel::clearedForMovement($parcel->getId())){
                return $this->response->sendError(ResponseMessage::PARCEL_NOT_CLEARED_FOR_TRANSIT);
            }

            $check = $parcel->checkout(Status::PARCEL_IN_TRANSIT, $held_by_id, $admin_id, ParcelHistory::MSG_IN_TRANSIT);
            if (!$check){
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    public function moveToForDeliveryAction(){
        $this->auth->allowOnly([Role::OFFICER]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');

        if (in_array(null, [$waybill_numbers])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number){
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_FOR_DELIVERY){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_FOR_DELIVERY;
                continue;
            } else if ($parcel->getStatus() != Status::PARCEL_ARRIVAL){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FROM_ARRIVAL;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch']['id']){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_OFFICE;
                continue;
            }

            $check = $parcel->changeStatus(Status::PARCEL_FOR_DELIVERY, $this->auth->getClientId(), ParcelHistory::MSG_FOR_DELIVERY);
            if (!$check){
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }



    public function moveToBeingDeliveredAction(){

    }

    public function moveToDeliveredAction(){

    }
} 