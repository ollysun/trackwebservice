<?php


class RegionController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $country_id = $this->request->getPost('country_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        if (in_array(null, [$country_id, $name, $description])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!Region::isExisting($country_id, $name)){
            $region = new Region();
            $region->initData($country_id, $name, $description);
            if ($region->save()){
                return $this->response->sendSuccess(['id' => $region->getId()]);
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::REGION_EXISTS);
    }

    public function editAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $region_id = $this->request->getPost('region_id');
        $country_id = $this->request->getPost('country_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        if (in_array(null, [$country_id, $name, $region_id, $description])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (Region::isExisting($country_id, $name, $region_id)){
            return $this->response->sendError(ResponseMessage::REGION_EXISTS);
        }

        $region = Region::fetchActive($region_id);
        if ($region != false){
            $region->edit($country_id, $name, $description);
            if ($region->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::REGION_DOES_NOT_EXIST);
    }

    public function changeActiveFgAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $region_id = $this->request->getPost('region_id');
        $active_fg = $this->request->getPost('active_fg'); // 0 or 1

        if (in_array(null, [$active_fg, $region_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!in_array($active_fg, [0,1])){
            return $this->response->sendError(ResponseMessage::INVALID_ACTIVE_FG);
        }

        $region = Region::findFirst([
            'id = :id:',
            'bind' => ['id' => $region_id]
        ]);
        if ($region != false){
            $region->changeActiveFg($active_fg);
            if ($region->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::REGION_DOES_NOT_EXIST);
    }

    public function changeStateRegionAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $state_id = $this->request->getPost('state_id');
        $region_id = $this->request->getPost('region_id');

        if (in_array(null, [$state_id, $region_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $region = Region::fetchActive($region_id);
        if ($region == false){
            return $this->response->sendError(ResponseMessage::REGION_DOES_NOT_EXIST);
        }

        $state = State::fetchOne($state_id);
        if ($state != false){
            $state->changeLocation($region->getId(), $region->getCountryId());
            if ($state->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::STATE_DOES_NOT_EXIST);
    }

    public function addCityAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $state_id = $this->request->getPost('state_id');
        $name = $this->request->getPost('name');

        if (in_array(null, [$state_id, $name])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (!City::isExisting($state_id, $name)){
            $city = new City();
            $city->initData($state_id, $name);
            if ($city->save()){
                return $this->response->sendSuccess(['id' => $city->getId()]);
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::CITY_EXISTS);
    }

    public function changeCityStatusAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $city_id = $this->request->getPost('city_id');
        $status = $this->request->getPost('status');

        if (in_array(null, [$city_id, $status])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $city = City::findFirst([
            'id = :id:',
            'bind' => ['id' => $city_id]
        ]);
        if ($city != false){
            $city->changeStatus($status);
            if ($city->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::CITY_DOES_NOT_EXIST);
    }

    public function editCityAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $city_id = $this->request->getPost('city_id');
        $state_id = $this->request->getPost('state_id');
        $name = $this->request->getPost('name');

        if (in_array(null, [$state_id, $name])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        if (City::isExisting($state_id, $name, $city_id)){
            return $this->response->sendError(ResponseMessage::REGION_EXISTS);
        }

        $city = City::fetchActive($city_id);
        if ($city != false){
            $city->edit($state_id, $name);
            if ($city->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::CITY_DOES_NOT_EXIST);
    }

    public function getOneCityAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $city_id = $this->request->getQuery('city_id');

        $with_state  = $this->request->getQuery('with_state');
        $with_country  = $this->request->getQuery('with_country');
        $with_region  = $this->request->getQuery('with_region');

        if (in_array(null, [$city_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $fetch_with = [];
        if (!is_null($with_state)){ $fetch_with['with_state'] = true; }
        if (!is_null($with_country)){ $fetch_with['with_country'] = true; }
        if (!is_null($with_region)){ $fetch_with['with_region'] = true; }

        $city = City::fetchOne($city_id, $fetch_with);
        if ($city != false){
            return $this->response->sendSuccess($city);
        }
        return $this->response->sendError(ResponseMessage::CITY_DOES_NOT_EXIST);
    }

    public function getAllCityAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $state_id = $this->request->getQuery('state_id');
        $country_id = $this->request->getQuery('country_id');
        $status = $this->request->getQuery('status');

        $with_state  = $this->request->getQuery('with_state');
        $with_country  = $this->request->getQuery('with_country');
        $with_region  = $this->request->getQuery('with_region');

        $filter_by = [];
        if (!is_null($state_id)){ $filter_by['state_id'] = $state_id; }
        if (!is_null($country_id)){ $filter_by['country_id'] = $country_id; }
        if (!is_null($status)){ $filter_by['status'] = $status; }

        $fetch_with = [];
        if (!is_null($with_state)){ $fetch_with['with_state'] = true; }
        if (!is_null($with_country)){ $fetch_with['with_country'] = true; }
        if (!is_null($with_region)){ $fetch_with['with_region'] = true; }

        return $this->response->sendSuccess(City::fetchAll($offset, $count, $filter_by, $fetch_with));
    }
} 