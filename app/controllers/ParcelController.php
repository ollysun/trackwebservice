<?php
use PhalconUtils\Validation\RequestValidation;

/**
 * Class ParcelController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Rahman Shitu <rahman@cottacush.com>
 * @author Olawale Lawal <wale@cottacush.com>
 */
class ParcelController extends ControllerBase
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     * @return $this
     */
    public function addAction()
    {
        //todo: must be tied to an EC Officer only
        $this->auth->allowOnly([Role::OFFICER]);
        $payload = $this->request->getJsonRawBody(true);
        Util::slackDebug("Parcel Payload >>", json_encode($payload));

        /*  $payload = '{
      "sender": {
          "firstname": "Rotimo",
          "lastname": "Akintewe",
          "phone": "+2348033438870",
          "email": "akintewe.rotimi@gmail.com"
      },
      "receiver": {
          "firstname": "Dapo",
          "lastname": "Olotu",
          "phone": "09023454321",
          "email": "dapo.olotu@gmail.com"
      },
      "sender_address": {
          "id": null,
          "street1": "3 Cuttacosh Road, Abule Egba.",
          "street2": "",
          "city_id": "23",
          "state_id": "1",
          "country_id": "1"
      },
      "receiver_address": {
          "id": null,
          "street1": "9, Ojo Street, Akoka",
          "street2": "",
          "city_id": "23",
          "state_id": "1",
          "country_id": "1"
      },
      "parcel": {
          "parcel_type": "1",
          "no_of_package": "1",
          "weight": "176",
          "parcel_value": "23000",
          "amount_due": "23000",
          "cash_on_delivery": 1,
          "cash_on_delivery_amount": "120000",
          "delivery_type": "2",
          "payment_type": "2",
          "shipping_type": "1",
          "other_info": "This is the other information needed",
          "cash_amount": null,
          "pos_amount": null,
          "pos_trans_id": null,
          "package_value": 200.00,
          "is_billing_overridden": 1,
          "reference_number": "R34324232"
      },
      "is_corporate_lead": 0,
      "to_hub": 1
  }';
          $payload = json_decode($payload, true);*/
        $sender = (isset($payload['sender'])) ? $payload['sender'] : null;
        $sender_address = (isset($payload['sender_address'])) ? $payload['sender_address'] : null;
        $receiver = (isset($payload['receiver'])) ? $payload['receiver'] : null;
        $receiver_address = (isset($payload['receiver_address'])) ? $payload['receiver_address'] : null;
        $bank_account = (isset($payload['bank_account'])) ? $payload['bank_account'] : null;
        $parcel = (isset($payload['parcel'])) ? $payload['parcel'] : null;
        $to_hub = (isset($payload['to_hub'])) ? $payload['to_hub'] : null;
        $is_corporate_lead = (isset($payload['is_corporate_lead'])) ? $payload['is_corporate_lead'] : null;
        $to_branch_id = (isset($payload['to_branch_id'])) ? $payload['to_branch_id'] : null;

        if (in_array(null, array($parcel, $sender, $sender_address, $receiver, $receiver_address)) or $to_hub === null) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $auth_data = $this->auth->getData();

        //Ensuring the officer is an EC or HUB officer
        if (!in_array($auth_data['branch']['branch_type'], [BranchType::EC, BranchType::HUB])) {
            return $this->response->sendAccessDenied();
        }

        //determining destination branch

        if ($to_hub > 0) {
            if ($auth_data['branch']['branch_type'] == BranchType::EC) {
                $to_branch = Branch::getParentById($auth_data['branch']['id']);
                if ($to_branch == null) {
                    return $this->response->sendError(ResponseMessage::EC_NOT_LINKED_TO_HUB);
                }

                $to_branch_id = $to_branch->getId();
            } else if ($to_branch_id == null) {
                return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS . ': Destination branch');
            }
        } else {
            $to_branch_id = $auth_data['branch']['id'];
        }

        //parcel no_of_package validation
        $parcel['no_of_package'] = intval($parcel['no_of_package']);
        if ($parcel['no_of_package'] < 1) {
            return $this->response->sendError(ResponseMessage::INVALID_PACKAGE_COUNT);
        }

        //parcel cash_amount and pos_amount sanitation
        switch ($parcel['payment_type']) {
            case PaymentType::CASH:
            case PaymentType::DEFERRED:
                $parcel['cash_amount'] = $parcel['amount_due'];
                $parcel['pos_amount'] = 0.0;
                break;
            case PaymentType::POS:
                $parcel['cash_amount'] = 0.0;
                $parcel['pos_amount'] = $parcel['amount_due'];
                break;
            case PaymentType::CASH_AND_POS:
                if (!isset($parcel['cash_amount']) or !isset($parcel['pos_amount'])) {
                    return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
                } else if ((bccomp($parcel['cash_amount'] + $parcel['pos_amount'], $parcel['amount_due'], 2) != 0)) {
                    return $this->response->sendError(ResponseMessage::INVALID_AMOUNT);
                }
                break;
            default:
                return $this->response->sendError(ResponseMessage::INVALID_PAYMENT_TYPE);
        }

        $parcel_obj = new Parcel();
        $waybill_numbers = $parcel_obj->saveForm($auth_data['branch']['id'], $sender, $sender_address, $receiver, $receiver_address,
            $bank_account, $parcel, $to_branch_id, $this->auth->getPersonId());
        if ($waybill_numbers) {
            // Link Parcel to Pickup Request
            if(isset($payload['pickup_request_id'])) {
                PickupRequest::linkParcelAndChangeStatus($payload['pickup_request_id'], $parcel_obj->getId());
            }

            if ($is_corporate_lead == 1) {
                $city = City::fetchOne($sender_address['city_id'], ['with_state' => 1, 'with_country' => 1]);
                if ($city != false) {
                    EmailMessage::send(
                        EmailMessage::CORPORATE_LEAD,
                        [
                            'firstname' => (isset($sender['firstname'])) ? ucfirst(strtolower($sender['firstname'])) : '',
                            'lastname' => (isset($sender['lastname'])) ? ucfirst(strtolower($sender['lastname'])) : '',
                            'street1' => (isset($sender_address['street1'])) ? $sender_address['street1'] : '',
                            'street2' => (isset($sender_address['street2'])) ? $sender_address['street2'] : '',
                            'city' => (isset($sender_address['city_id'])) ? ucwords(strtolower($city['name'])) : '',
                            'state' => (isset($sender_address['city_id'])) ? ucwords(strtolower($city['state']['name'])) : '',
                            'country' => (isset($sender_address['city_id'])) ? ucwords(strtolower($city['country']['name'])) : '',
                            'email' => (isset($sender['email'])) ? $sender['email'] : '',
                            'phone' => (isset($sender['phone'])) ? $sender['phone'] : '',
                            'year' => date('Y')
                        ],
                        'Courier Plus [' . strtoupper($auth_data['branch']['name']) . ']');
                }
            }
            return $this->response->sendSuccess(['id' => $parcel_obj->getId(), 'waybill_number' => $waybill_numbers]);
        }
        return $this->response->sendError();
    }

    public function getOneAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::GROUNDSMAN]);

        $id = $this->request->getQuery('id');
        $waybill_number = $this->request->getQuery('waybill_number');
        $with_linked = $this->request->getQuery('with_linked');

        if (is_null($id) && is_null($waybill_number)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (is_null($waybill_number)) {
            $value = $id;
            $fetch_with = 'id';
        } else {
            $value = $waybill_number;
            $fetch_with = 'waybill_number';
        }

        $with_linked = ($with_linked == 0 || $with_linked == 'false') ? false : true;

        $parcel = Parcel::fetchOne($value, $with_linked, $fetch_with);
        if ($parcel != false) {
            return $this->response->sendSuccess($parcel);
        }
        return $this->response->sendError(ResponseMessage::NO_RECORD_FOUND);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     */
    private function getFilterParams()
    {
        $filter_params = ['manifest_id','show_parents','parent_id','entity_type','is_visible','created_by','user_id','held_by_staff_id','held_by_id','to_branch_id','from_branch_id','parcel_type','sender_id','sender_address_id','receiver_id','receiver_address_id','status','min_weight','max_weight','min_amount_due','max_amount_due','cash_on_delivery','min_delivery_amount','max_delivery_amount','delivery_type','payment_type','shipping_type','min_cash_amount','max_cash_amount','min_pos_amount','max_pos_amount','start_created_date','end_created_date','start_modified_date','end_modified_date','waybill_number','waybill_number_arr','created_branch_id','route_id','history_status','history_start_created_date','history_end_created_date','history_from_branch_id','history_to_branch_id', 'request_type'];

        $filter_by = [];
        foreach ($filter_params as $param) {
            $$param = $this->request->getQuery($param);
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        return $filter_by;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getAllAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $with_to_branch = $this->request->getQuery('with_to_branch');
        $with_from_branch = $this->request->getQuery('with_from_branch');
        $with_sender = $this->request->getQuery('with_sender');
        $with_sender_address = $this->request->getQuery('with_sender_address');
        $with_receiver = $this->request->getQuery('with_receiver');
        $with_receiver_address = $this->request->getQuery('with_receiver_address');
        $with_holder = $this->request->getQuery('with_holder');
        $with_bank_account = $this->request->getQuery('with_bank_account');
        $with_created_branch = $this->request->getQuery('with_created_branch');
        $with_route = $this->request->getQuery('with_route');
        $with_created_by = $this->request->getQuery('with_created_by');

        $with_total_count = $this->request->getQuery('with_total_count');
        $send_all = $this->request->getQuery('send_all');

        $order_by = $this->request->getQuery('order_by'); //'Parcel.created_date DESC'

        $filter_by = $this->getFilterParams();

        if (!is_null($send_all)) {
            $filter_by['send_all'] = true;
        }

        $fetch_with = [];
        if (!is_null($with_to_branch)) {
            $fetch_with['with_to_branch'] = true;
        }
        if (!is_null($with_from_branch)) {
            $fetch_with['with_from_branch'] = true;
        }
        if (!is_null($with_sender)) {
            $fetch_with['with_sender'] = true;
        }
        if (!is_null($with_receiver)) {
            $fetch_with['with_receiver'] = true;
        }
        if (!is_null($with_sender_address)) {
            $fetch_with['with_sender_address'] = true;
        }
        if (!is_null($with_receiver_address)) {
            $fetch_with['with_receiver_address'] = true;
        }
        if (!is_null($with_holder)) {
            $fetch_with['with_holder'] = true;
        }
        if (!is_null($with_bank_account)) {
            $fetch_with['with_bank_account'] = true;
        }
        if (!is_null($with_created_branch)) {
            $fetch_with['with_created_branch'] = true;
        }
        if (!is_null($with_route)) {
            $fetch_with['with_route'] = true;
        }
        if (!is_null($with_created_by)) {
            $fetch_with['with_created_by'] = true;
        }

        $parcels = Parcel::fetchAll($offset, $count, $filter_by, $fetch_with, $order_by);
        $result = [];
        if ($with_total_count != null) {
            $count = Parcel::parcelCount($filter_by);
            $result = [
                'total_count' => $count,
                'parcels' => $parcels
            ];
        } else {
            $result = $parcels;
        }

        return $this->response->sendSuccess($result);
    }

    public function countAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER, Role::SWEEPER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $filter_by = $this->getFilterParams();

        $count = Parcel::parcelCount($filter_by);
        if ($count === null) {
            return $this->response->sendError();
        }
        return $this->response->sendSuccess($count);
    }

    private function sanitizeWaybillNumbers($waybill_numbers)
    {
        $waybill_number_arr = explode(',', $waybill_numbers);

        $clean_arr = [];
        foreach ($waybill_number_arr as $number) {
            $clean_arr[trim(strtoupper($number))] = true;
        }

        return array_keys($clean_arr);
    }

    public function moveToForSweeperAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::GROUNDSMAN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $to_branch_id = $this->request->getPost('to_branch_id');

        if (in_array(null, [$waybill_numbers, $to_branch_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_FOR_SWEEPER) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_FOR_SWEEPER;
                continue;
            } else if (!in_array($parcel->getStatus(), [Status::PARCEL_ARRIVAL, Status::PARCEL_FOR_GROUNDSMAN])) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FROM_ARRIVAL;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch']['id']) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_OFFICE;
                continue;
            }

            $check = $parcel->changeDestination(Status::PARCEL_FOR_SWEEPER, $to_branch_id, $this->auth->getPersonId(), ParcelHistory::MSG_FOR_SWEEPER);
            if (!$check) {
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    public function bagAction()
    {
        $this->auth->allowOnly([Role::OFFICER]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $to_branch_id = $this->request->getPost('to_branch_id');
        $status = $this->request->getPost('status');
        $seal_id = $this->request->getPost('seal_id', null, '');

        if (in_array(null, [$waybill_numbers, $to_branch_id, $status])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        if (count($waybill_number_arr) == 0) {
            return $this->response->sendError(ResponseMessage::NO_PARCEL_TO_BAG);
        }

        $auth_data = $this->auth->getData();

        $bag_info = Parcel::bagParcels($auth_data['branch']['id'], $to_branch_id, $this->auth->getPersonId(), $status, $waybill_number_arr, $seal_id);
        if ($bag_info != false) {
            return $this->response->sendSuccess($bag_info);
        }
        return $this->response->sendError();
    }

    public function openBagAction()
    {
        $this->auth->allowOnly([Role::OFFICER]);

        $bag_waybill_number = $this->request->getPost('waybill_number');

        if (in_array(null, [$bag_waybill_number])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $check = Parcel::unbagParcels($bag_waybill_number);
        if ($check) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError();
    }

    public function moveToArrivalAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::GROUNDSMAN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $held_by_id = $this->request->getPost('held_by_id');

        if (in_array(null, [$waybill_numbers, $held_by_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_ARRIVAL) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_IN_ARRIVAL;
                continue;
            } else if ($parcel->getStatus() != Status::PARCEL_IN_TRANSIT) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_TRANSIT;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch']['id']) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_WRONG_DESTINATION;
                continue;
            }

            //checking if the parcel is held by the correct person
            $held_parcel_record = HeldParcel::fetchUncleared($parcel->getId(), $held_by_id);
            if ($held_parcel_record == false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_HELD_BY_WRONG_OFFICIAL;
                continue;
            }

            $check = $parcel->checkIn($held_parcel_record, $this->auth->getPersonId());
            if (!$check) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_CANNOT_BE_CLEARED;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    /**
     * Move package to in transit
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     * @return $this
     */
    public function moveToInTransitAction()
    {
        $this->auth->allowOnly([Role::SWEEPER, Role::OFFICER, Role::DISPATCHER, Role::GROUNDSMAN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $to_branch_id = $this->request->getPost('to_branch_id');
        $held_by_id = (in_array($this->auth->getUserType(), [Role::SWEEPER, Role::DISPATCHER])) ? $this->auth->getPersonId() : $this->request->getPost('held_by_id');
        $admin_id = (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) ? $this->auth->getPersonId() : $this->request->getPost('admin_id');
        $label = $this->request->getPost('label', null, '');

        if (!isset($waybill_numbers, $to_branch_id, $held_by_id, $admin_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $other_id = (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) ? $held_by_id : $admin_id;
        $other = Admin::getById($other_id);

        if ($other != false) {
            //check if officer is valid
            if (in_array($this->auth->getUserType(), [Role::SWEEPER, Role::DISPATCHER]) && !in_array($other->getRoleId(), [Role::OFFICER, Role::GROUNDSMAN])) {
                return $this->response->sendError(ResponseMessage::INVALID_OFFICER);
            }

            //check if sweeper or dispatcher is valid
            if (!in_array($other->getRoleId(), [Role::SWEEPER, Role::DISPATCHER]) && in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) {
                return $this->response->sendError(ResponseMessage::INVALID_SWEEPER_OR_DISPATCHER);
            }

        } else {
            //if currently logged in user is a dispatcher or sweeper the officer is invalid
            if (in_array($this->auth->getUserType(), [Role::SWEEPER, Role::DISPATCHER])) {
                return $this->response->sendError(ResponseMessage::INVALID_OFFICER);

                //if currently logged in user is an officer or groundsman the sweeper or dispatcher is invalid
            } else if (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) {
                return $this->response->sendError(ResponseMessage::INVALID_SWEEPER_OR_DISPATCHER);
            }
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();
        $user_branch_id = $auth_data['branch_id']; // logged on user's branch

        $parcel_arr = Parcel::getByWaybillNumberList($waybill_number_arr, true);
        $bad_parcel = [];

        $from_branch_id = (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) ? $user_branch_id : $other->getBranchId();

        /**
         * @var Parcel $parcel
         */
        foreach ($waybill_number_arr as $waybill_number) {
            if (!isset($parcel_arr[$waybill_number])) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            $parcel = $parcel_arr[$waybill_number];

            if ($parcel->getStatus() == Status::PARCEL_IN_TRANSIT) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_IN_TRANSIT;
            } else if ($parcel->getFromBranchId() != $from_branch_id) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_OFFICER_BRANCH;
            } else if ($parcel->getStatus() != Status::PARCEL_FOR_SWEEPER) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FOR_SWEEPING;
            } else if ($parcel->getToBranchId() != $to_branch_id) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_HEADING_TO_DESTINATION;
            } else if (!HeldParcel::clearedForMovement($parcel->getId())) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_CLEARED_FOR_TRANSIT;
            }

            //Removing bad parcel before adding to manifest
            if (isset($bad_parcel[$waybill_number])) {
                unset($parcel_arr[$waybill_number]);
            }
        }

        $check = Manifest::createOne($parcel_arr, $label, $from_branch_id, $to_branch_id, $admin_id, $held_by_id, Manifest::TYPE_SWEEP);
        if ($check == false) {
            return $this->response->sendError();
        }

        /** @var Manifest $manifest */
        $manifest = isset($check['manifest']) ? $check['manifest'] : null;
        $bad_parcels = array_merge($check['bad_parcels'], $bad_parcel);

        //send out notification for parcels in transit that are not bad
        $admin = Admin::findFirst($admin_id);

        if ($admin && !is_null($manifest)) {
            /** @var  $originBranch  Branch */
            $originBranch = Branch::findFirst($from_branch_id);
            /** @var  $destinationBranch Branch */
            $destinationBranch = Branch::findFirst($to_branch_id);
            EmailMessage::send(
                EmailMessage::PARCEL_IN_TRANSIT,
                [
                    'fullname' => ucwords($admin->getFullname()),
                    'manifest_label' => $manifest->getLabel(),
                    'origin' => $originBranch->getName(),
                    'destination' => $destinationBranch->getName()
                ],
                'Courier Plus',
                $admin->getEmail()
            );
        }

        //set response data
        $check['manifest_id'] = null;
        if (!is_null($manifest)) {
            $check['manifest'] = $manifest->getData();
            $check['manifest_id'] = $manifest->getId();
        }
        $check['bad_parcels'] = $bad_parcels;
        return $this->response->sendSuccess($check);
    }

    public function moveToForDeliveryAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::GROUNDSMAN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $route_id = $this->request->getPost('route_id');
        $admin_id = $this->auth->getPersonId();

        if (is_null($waybill_numbers)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_FOR_DELIVERY) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_FOR_DELIVERY;
                continue;
            } else if (!in_array($parcel->getStatus(), [Status::PARCEL_ARRIVAL, Status::PARCEL_FOR_GROUNDSMAN])) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FROM_ARRIVAL;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch']['id']) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_WRONG_DESTINATION;
                continue;
            }

            $parcel->setRouteId($route_id);
            $check = $parcel->changeStatus(Status::PARCEL_FOR_DELIVERY, $admin_id, ParcelHistory::MSG_FOR_DELIVERY, $auth_data['branch_id']);
            if (!$check) {
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     * @return $this
     */
    public function moveToBeingDeliveredAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::DISPATCHER, Role::SWEEPER, Role::GROUNDSMAN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $held_by_id = (in_array($this->auth->getUserType(), [Role::SWEEPER, Role::DISPATCHER])) ? $this->auth->getPersonId() : $this->request->getPost('held_by_id');
        $admin_id = (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) ? $this->auth->getPersonId() : $this->request->getPost('admin_id');
        $label = $this->request->getPost('label', null, '');

        if (in_array(null, [$waybill_numbers, $held_by_id, $admin_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $other_id = (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) ? $held_by_id : $admin_id;
        $other = Admin::getById($other_id);

        if ($other != false) {
            //check if officer is valid
            if (in_array($this->auth->getUserType(), [Role::SWEEPER, Role::DISPATCHER]) && !in_array($other->getRoleId(), [Role::OFFICER, Role::GROUNDSMAN])) {
                return $this->response->sendError(ResponseMessage::INVALID_OFFICER);
            }

            //check if sweeper or dispatcher is valid
            if (!in_array($other->getRoleId(), [Role::SWEEPER, Role::DISPATCHER]) && in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) {
                return $this->response->sendError(ResponseMessage::INVALID_SWEEPER_OR_DISPATCHER);
            }

        } else {
            //if currently logged in user is a dispatcher or sweeper the officer is invalid
            if (in_array($this->auth->getUserType(), [Role::SWEEPER, Role::DISPATCHER])) {
                return $this->response->sendError(ResponseMessage::INVALID_OFFICER);

                //if currently logged in user is an officer or groundsman the sweeper or dispatcher is invalid
            } else if (in_array($this->auth->getUserType(), [Role::OFFICER, Role::GROUNDSMAN])) {
                return $this->response->sendError(ResponseMessage::INVALID_SWEEPER_OR_DISPATCHER);
            }
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();
        $user_branch_id = $auth_data['branch_id']; // logged on user's branch

        $parcel_arr = Parcel::getByWaybillNumberList($waybill_number_arr, true);
        $bad_parcel = [];

        /**
         * @var Parcel $parcel
         */
        foreach ($waybill_number_arr as $waybill_number) {
            if (!isset($parcel_arr[$waybill_number])) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            $parcel = $parcel_arr[$waybill_number];

            if ($parcel->getStatus() == Status::PARCEL_BEING_DELIVERED) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_FOR_BEING_DELIVERED;
            } else if ($parcel->getStatus() != Status::PARCEL_FOR_DELIVERY) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FOR_DELIVERY;
            } else if ($parcel->getToBranchId() != $user_branch_id) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_OFFICE;
            }

            //Removing bad parcel before adding to manifest
            if (isset($bad_parcel[$waybill_number])) {
                unset($parcel_arr[$waybill_number]);
            }
        }

        $check = Manifest::createOne($parcel_arr, $label, $user_branch_id, $user_branch_id, $admin_id, $held_by_id, Manifest::TYPE_DELIVERY);
        if ($check == false) {
            return $this->response->sendError();
        }

        /** @var Manifest $manifest */
        $manifest = isset($check['manifest']) ? $check['manifest'] : null;
        $bad_parcels = array_merge($check['bad_parcels'], $bad_parcel);

        $admin = Admin::findFirst($admin_id);


        //send notification if manifest was created
        if ($admin && !is_null($manifest)) {
            /** @var  $originBranch  Branch */
            $originBranch = Branch::findFirst($user_branch_id);
            /** @var  $destinationBranch Branch */
            $destinationBranch = Branch::findFirst($user_branch_id);
            EmailMessage::send(
                EmailMessage::PARCEL_IN_TRANSIT,
                [
                    'fullname' => ucwords($admin->getFullname()),
                    'manifest_label' => $manifest->getLabel(),
                    'origin' => $originBranch->getName(),
                    'destination' => $destinationBranch->getName()
                ],
                'Courier Plus',
                $admin->getEmail()
            );
        }

        //set response data
        $check['manifest_id'] = null;
        if (!is_null($manifest)) {
            $check['manifest'] = $manifest->getData();
            $check['manifest_id'] = $manifest->getId();
        }
        $check['bad_parcels'] = $bad_parcels;
        return $this->response->sendSuccess($check);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @author Rahman Shitu <rahman@cottacush.com>
     * @author Olawale Lawal <wale@cottacush.com>
     * @return $this
     */
    public
    function moveToDeliveredAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::DISPATCHER, Role::SWEEPER, Role::GROUNDSMAN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $admin_id = $this->auth->getPersonId();

        if (in_array(null, [$waybill_numbers, $admin_id])) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        $admin = Admin::findFirst($admin_id);
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_DELIVERED) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_DELIVERED;
                continue;
            } else if ($parcel->getStatus() != Status::PARCEL_BEING_DELIVERED) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FOR_DELIVERY;
                continue;
            }

            $check = $parcel->changeStatus(Status::PARCEL_DELIVERED, $admin_id, ParcelHistory::MSG_DELIVERED, $auth_data['branch_id']);
            if (!$check) {
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }

            //get recipients
            $recipients = [];
            if ($admin) {
                $recipients[$admin->getEmail()] = $admin->getFullname();
            }
            $sender = User::findFirst($parcel->getSenderId());
            if ($sender) {
                $recipients[$sender->getEmail()] = $sender->getFirstname();
            }

            $receiver = User::findFirst($parcel->getReceiverId());
            if ($receiver) {
                $recipients[$receiver->getEmail()] = $receiver->getFirstname();
            }

            foreach ($recipients as $email => $name) {
                EmailMessage::send(
                    EmailMessage::PARCEL_DELIVERED,
                    [
                        'firstname' => ucwords($name),
                        'waybill_number' => $waybill_number,
                    ],
                    'Courier Plus',
                    $email
                );
            }

            //TODO send email to operations team
        }

        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    /**
     * Marks a shipment as CANCELLED
     *
     * @author  Olawale Lawal
     * @return array
     */
    public
    function cancelAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::ADMIN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $admin_id = $this->auth->getPersonId();

        if (!isset($waybill_numbers, $admin_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() == Status::PARCEL_CANCELLED) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_CANCELLED;
                continue;
            } else if (!in_array($parcel->getStatus(), [Status::PARCEL_FOR_SWEEPER, Status::PARCEL_FOR_DELIVERY])) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_CANNOT_BE_CANCELLED;
                continue;
            }

            $check = $parcel->changeStatus(Status::PARCEL_CANCELLED, $admin_id, ParcelHistory::MSG_CANCELLED, $auth_data['branch_id'], true);
            if (!$check) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_CANNOT_BE_CANCELLED;
                continue;
            }
        }
        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    /**
     * Marks a shipment for GROUNDSMAN
     *
     * @author  Olawale Lawal
     * @return array
     */
    public
    function assignToGroundsmanAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::ADMIN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $admin_id = $this->auth->getPersonId();

        if (!isset($waybill_numbers, $admin_id)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $bad_parcel = [];
        foreach ($waybill_number_arr as $waybill_number) {
            $parcel = Parcel::getByWaybillNumber($waybill_number);
            if ($parcel === false) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            if ($parcel->getStatus() != Status::PARCEL_ARRIVAL) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_FROM_ARRIVAL;
                continue;
            } else if ($parcel->getToBranchId() != $auth_data['branch_id']) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_IN_OFFICE;
                continue;
            }
            $check = $parcel->changeDestination(Status::PARCEL_FOR_GROUNDSMAN, $auth_data['branch_id'], $admin_id, ParcelHistory::MSG_ASSIGNED_TO_GROUNDSMAN);
            if (!$check) {
                $bad_parcel[$waybill_number] = ResponseMessage::CANNOT_MOVE_PARCEL;
                continue;
            }
        }
        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }

    public function historyAction()
    {
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $filter_params = [
            'waybill_number', 'parcel_id', 'paginate', 'status'
        ];

        $fetch_params = ['with_admin'];

        //todo: make this block a function
        //------------------------------------------
        $possible_params = array_merge($filter_params, $fetch_params);

        foreach ($possible_params as $param) {
            $$param = $this->request->getQuery($param);
        }

        $filter_by = [];
        foreach ($filter_params as $param) {
            if (!is_null($$param)) {
                $filter_by[$param] = $$param;
            }
        }

        $fetch_with = [];
        foreach ($fetch_params as $param) {
            if (!is_null($$param)) {
                $fetch_with[$param] = true;
            }
        }
        //------------------------------------------

        return $this->response->sendSuccess(ParcelHistory::fetchAll($offset, $count, $filter_by, $fetch_with));
    }

    /**
     * Add a parcel delivery receipt
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function createParcelDeliveryReceiptAction()
    {
        $waybill_number = $this->request->getPost('waybill_number', null, null);
        $delivered_by = $this->request->getPost('delivered_by', null, null);
        $email = $this->request->getPost('email', null, null);
        $phone_number = $this->request->getPost('phone_number', null, null);
        $name = $this->request->getPost('name', null, null);

        $requiredFields = ['waybill_number', 'delivered_by', 'phone_number', 'name'];
        $data = ['waybill_number' => $waybill_number, 'delivered_by' => $delivered_by, 'phone_number' => $phone_number, 'name' => $name];

        $requestValidator = new RequestValidation($data);
        $requestValidator->setRequiredFields($requiredFields);
        if (!$requestValidator->validate()) {
            return $this->response->sendError($requestValidator->getMessages());
        }

        $data['email'] = $email;

        //check if parcel with waybill_number exists
        $parcel = Parcel::findFirstByWaybillNumber($waybill_number);
        if (!$parcel) {
            return $this->response->sendError('Parcel with waybill number does not exist');
        }

        //check if delivering user exists
        $user = Admin::findFirst($delivered_by);
        if (!$user) {
            return $this->response->sendError('Invalid delivery user');
        }

        //check if post has files
        if (!$this->request->hasFiles()) {
            return $this->response->sendError('signature or snapshot file required');
        }

        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $receipt_paths = [];

        /** @var \Phalcon\Http\Request\File $file */
        foreach ($this->request->getUploadedFiles() as $file) {
            if (in_array($file->getKey(), ['signature', 'snapshot'])) {
                if (in_array($file->getExtension(), $allowedExtensions)) {
                    if (DeliveryReceipt::doesReceiptExist($waybill_number, $file->getKey())) {
                        return $this->response->sendError($file->getKey() . ' receipt for parcel already exists');
                    }

                    $receipt_file_name = $waybill_number . "_" . $file->getKey() . "_receipt." . $file->getExtension();
                    $receipt_path = $this->s3Client->createObject($file->getTempName(), $receipt_file_name);
                    if (!$receipt_path) {
                        return $this->response->sendError('Could not upload ' . $file->getKey() . ' receipt to S3');
                    }

                    $receipt_paths[] = $receipt_path;

                    $data['receipt_path'] = $receipt_path;
                    $data['receipt_type'] = $file->getKey();
                    if (!DeliveryReceipt::add($data)) {
                        return $this->response->sendError('Could not save ' . $file->getKey() . ' receipt');
                    }

                } else {
                    return $this->response->sendError('Invalid extension for ' . $file->getKey() . '. Only ' . implode(', ', $allowedExtensions) . ' allowed');
                }
            }
        }

        return $this->response->sendSuccess(['receipts' => $receipt_paths]);
    }

    /**
     * Used to set the return flag of a parcel
     * @author Rahman Shitu <rahman@cottacush.com>
     * @return $this
     */
    public function setReturnFlagAction()
    {
        $this->auth->allowOnly([Role::OFFICER, Role::ADMIN]);

        $waybill_numbers = $this->request->getPost('waybill_numbers');
        $return_flag = $this->request->getPost('return_flag', null, 1);

        if (!in_array($return_flag,[0,1])){
            return $this->response->sendError(ResponseMessage::INVALID_VALUES);
        }

        if (!isset($waybill_numbers)) {
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $waybill_number_arr = $this->sanitizeWaybillNumbers($waybill_numbers);
        $auth_data = $this->auth->getData();

        $parcel_arr = Parcel::getByWaybillNumberList($waybill_number_arr, true);
        $bad_parcel = [];

        /**
         * @var Parcel $parcel
         */
        foreach ($waybill_number_arr as $waybill_number) {
            if (!isset($parcel_arr[$waybill_number])) {
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_EXISTING;
                continue;
            }

            $parcel = $parcel_arr[$waybill_number];

            //cannot flag a return for a delivered parcel
            if ($parcel->getStatus() == Status::PARCEL_DELIVERED){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_ALREADY_DELIVERED;
            }

            //if it is an officer, his branch must be either the to or from branch id
            if ($this->auth->getUserType() == Role::OFFICER && !in_array($auth_data['branch_id'], [$parcel->getToBranchId(), $parcel->getFromBranchId()])){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_NOT_ACCESSIBLE;
            }

            //bags and split parcel parent can not be returned
            if (in_array($parcel->getEntityType(), [Parcel::ENTITY_TYPE_BAG, Parcel::ENTITY_TYPE_PARENT])){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_CANNOT_CHANGE_RETURN_FLAG;
            }

            $parcel->setForReturn($return_flag);
            if (!$parcel->save()){
                $bad_parcel[$waybill_number] = ResponseMessage::PARCEL_CANNOT_CHANGE_RETURN_FLAG;
                continue;
            }
        }
        return $this->response->sendSuccess(['bad_parcels' => $bad_parcel]);
    }
}
