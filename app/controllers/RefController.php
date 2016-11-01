<?php


//todo: cache all responses
class RefController extends ControllerBase {
    public function countriesAction(){
        return $this->response->sendSuccess(Ref::fetch('Country'));
    }

    public function banksAction(){
        return $this->response->sendSuccess(Ref::fetch('Bank'));
    }

    public function statesAction(){
        $country_id = $this->request->getQuery('country_id');//optional
        $region_id = $this->request->getQuery('region_id');//optional
        $with_region = $this->request->getQuery('with_region');

        $filter_by = [];
        if (!is_null($country_id)) {$filter_by['country_id'] = $country_id;}
        if (!is_null($region_id)) {$filter_by['region_id'] = $region_id;}

        $fetch_with = [];
        if (!is_null($with_region)) {$fetch_with['with_region'] = true;}

        $data = State::fetchAll($filter_by, $fetch_with);
        return $this->response->sendSuccess($data);
    }

    public function shipmentTypeAction(){
        return $this->response->sendSuccess(Ref::fetch('ShippingType'));
    }

    public function regionAction(){
        $country_id = $this->request->getQuery('country_id');
        $with_manager = $this->request->getQuery('with_manager');

        if (is_null($country_id)){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if($with_manager == '1'){
            $data = Region::getAll($country_id);
        }else{
            $data = Region::find([
                'country_id = :country_id: AND active_fg=1',
                'bind' => ['country_id' => $country_id]
            ]);
        }

        return $this->response->sendSuccess($data->toArray());
    }

    public function paymentTypeAction(){
        return $this->response->sendSuccess(Ref::fetch('PaymentType'));
    }

    public function parcelTypeAction(){
        return $this->response->sendSuccess(Ref::fetch('ParcelType'));
    }

    public function deliveryTypeAction(){
        return $this->response->sendSuccess(Ref::fetch('DeliveryType'));
    }

    public function rolesAction(){
        return $this->response->sendSuccess(Ref::fetch('Role'));
    }
} 