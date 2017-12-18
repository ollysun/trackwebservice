<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 1/3/2017
 * Time: 12:29 AM
 */
class GeoController extends ControllerBase
{
    public function saveCountryAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $country_name = $this->request->getPost('name');
        $id = $this->getPost('id');

        $country = Country::findFirstByName($country_name);
        if($country && $country->getId() != $id){
            return $this->response->sendError('Duplicate Error');
        }

        if($id) {
            $country = Country::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
            if(!$country) return $this->response->sendError('Country not found');
        }
        else $country = new Country();
        $country->setName($country_name);

        if($country->save()){
            return $this->response->sendSuccess($country->getId());
        }
        return $this->response->sendError();

    }


    public function saveRegionAction(){
        $id = $this->getPost('id');
        $is_new = $id == null || $id < 1;
        $name = $this->getPost('name');
        $country_id = $this->getPost('country_id');
        $description = $this->getPost('description');

        if($region = Region::findFirst(['conditions' => 'name = :name: AND country_id = :country_id:',
            'bind' => ['name' => $name, 'country_id' => $country_id]])){
            if($region->getId() != $id)
                return $this->response->sendError('Duplicate Error');
        }

        $region = $is_new? new Region(): Region::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if($is_new) $region->initData($country_id, $name, $description);
        else $region->edit($country_id, $name, $description);

        if($region->save()) return $this->response->sendSuccess($region->getId());
        return $this->response->sendError(implode(',', $region->getMessages()));

    }

    public function saveStateAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $name = $this->request->getPost('name');
        $country_id = $this->getPost('country_id');
        $code = $this->getPost('code');
        $region_id = $this->getPost('region_id');
        $state_id = $this->getPost('id');

        if(in_array(null, [$name, $country_id, $code, $region_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $state = State::findFirst(['conditions' => 'name = :name:',
            'bind' => ['name' => $name]]);
        if($state && $state->getId() != $state_id){
            return $this->response->sendError('Duplicate Error');
        }

        if($state_id) {
            $state = State::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $state_id]]);
            if(!$state) return $this->response->sendError('State not found');
        }
        else $state = new State();
        $state->setCode($code);
        $state->setCountryId($country_id);
        $state->setName($name);
        $state->setRegionId($region_id);

        if($state->save()){
            return $this->response->sendSuccess($state->getId());
        }

        return $this->response->sendError();

    }

    public function saveCityAction(){
        $name = $this->getPost('name');
        $state_id = $this->getPost('state_id');
        $branch_id = $this->getPost('branch_id');
        $id = $this->getPost('id');

        if(in_array(null, [$name, $state_id, $branch_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if(!State::findFirstById($state_id)){
            return $this->response->sendError("Invalid state Id");
        }

        if(!Branch::findFirstById($branch_id)){
            return $this->response->sendError('Invalid branch Id');
        }

        $city = City::findFirst(['conditions' => 'name = :name: AND state_id = :state_id:',
            'bind' => ['name' => $name, 'state_id' => $state_id]]);
        if($city && $city->getId() != $id){
           return $this->response->sendError('Duplicate city name');
        }

        if($id){
            $city = City::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
            if(!$city) return $this->response->sendError('City not found');
            $city->edit($state_id, $name, $branch_id, 0);
        }else {
            $city = new City();
            $city->initData($state_id, $name, $branch_id, 0);
        }


        if($city->save()) return $this->response->sendSuccess($city->getId());
        return $this->response->sendError();
    }



}