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
        $country_id = $this->request->getQuery('country_id');

        if ($country_id == null){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $data = Ref::fetch(
            'State',
            'country_id = :country_id:',
            array('country_id' => $country_id)
        );
        return $this->response->sendSuccess($data);
    }

    public function shipmentTypeAction(){
        return $this->response->sendSuccess(Ref::fetch('ShippingType'));
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