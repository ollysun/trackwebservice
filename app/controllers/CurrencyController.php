<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/2/2017
 * Time: 4:55 AM
 */


class CurrencyController extends ControllerBase
{
    public function getAction(){
        $country_id = $this->get('country_id');
        $code = $this->get('code');
        $id = $this->get('id');

        if($country_id == null && $code == null && $id == null){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $currency = Currency::findFirst(['conditions' => 'country_id = :country_id: OR code = :code: OR id = :id:',
            'bind' => ['country_id' => $country_id, 'code' => $code, 'id' => $id]]);

        if($currency) return $this->response->sendSuccess($currency);
        return $this->response->sendError('Currency not found');
    }

    public function getAllAction(){
        $with_country = $this->request->getQuery("with_country");
        $country_id = $this->request->getQuery('country_id');
        $offset = $this->request->getQuery('offset');
        $count = $this->request->getQuery('count');
        $send_all = $this->request->getQuery('send_all', null, false);

        $filter = [];
        $fetch_with = [];

        if($with_country) $fetch_with['with_country'] = true;
        if($country_id) $filter['country_id'] = true;

        $currencies = Currency::fetchAll($offset, $count, $filter, $fetch_with, $send_all);
        if($send_all) return $this->response->sendSuccess($currencies);
        $total_count = Currency::getCount($filter);
        return $this->response->sendSuccess(['total_count' => $total_count, 'currencies' => $currencies]);
    }

    public function addAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $name = $this->getPost('name');
        $code = $this->getPost('code');
        $decimal_unicode = $this->getPost('decimal_unicode');
        $hexadecimal_unicode = $this->getPost('hexadecimal_unicode');
        $conversion_rate = $this->getPost('conversion_rate');
        $country_id = $this->getPost('country_id');

        if(in_array(null, [$name,$code, $country_id, $decimal_unicode])){
            $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        if($hexadecimal_unicode == null && $decimal_unicode == null){
            return $this->response->sendError('You must specify the hexadecimal of the decimal unicode for the currency');
        }

        if(Currency::findFirst(['conditions' => 'Currency.name = :name:', 'bind' => ['name' => $name]])){
            return $this->response->sendError('A currency with the same name already exists');
        }

        if(Currency::findFirst(['conditions' => 'Currency.code = :code:', 'bind' => ['code' => $code]])){
            return $this->response->sendError('A currency with the same code already exists');
        }

        $currency = new Currency();
        $currency->init(['name' => $name, 'code' => $code, 'decimal_unicode' => $decimal_unicode,
            'hexadecimal_unicode' => $hexadecimal_unicode, 'conversion_rate' => $conversion_rate, 'country_id' => $country_id]);
        if($currency->save()){
            return $this->response->sendSuccess();
        }
        Util::slackDebug('Currency not save', implode(', ', $currency->getMessages()));
        return $this->response->sendError();
    }

    public function changeConversionRateAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->getPost('id');
        $conversion_rate = $this->getPost('conversion_rate');

        $currency = Currency::findFirst(['conditions' => 'Currency.id = :id:', 'bind' => ['id' => $id]]);
        if(!$currency){
            return $this->response->sendError('Currency not found');
        }

        $currency->setConversionRate($conversion_rate);
        if($currency->save()){
            return $this->response->sendSuccess();
        }
        Util::slackDebug('Currency not saved', implode(', ', $currency->getMessages()));
        return $this->response->sendError();
    }
}