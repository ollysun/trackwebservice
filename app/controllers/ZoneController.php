<?php


class ZoneController extends ControllerBase {
    public function addAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');

        if (in_array(null, [$name, $code])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchByDetails($name, $code);
        if ($zone != false){
            if ($zone->hasSameName($name)){
                return $this->response->sendError(ResponseMessage::ZONE_NAME_EXISTS);
            }else if ($zone->hasSameCode($code)){
                return $this->response->sendError(ResponseMessage::ZONE_CODE_EXISTS);
            }
            return $this->response->sendError();
        }

        $zone = new Zone();
        $zone->initData($name, $code, $description);
        if ($zone->save()){
            return $this->response->sendSuccess(['id' => $zone->getId()]);
        }
        return $this->response->sendError();
    }

    public function editAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $zone_id = $this->request->getPost('zone_id');
        $name = $this->request->getPost('name');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description', null, ' ');

        if (in_array(null, [$zone_id, $name, $code])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchByDetails($name, $code, $zone_id);

        if ($zone != false){
            if ($zone->hasSameName($name)){
                return $this->response->sendError(ResponseMessage::ZONE_NAME_EXISTS);
            }else if ($zone->hasSameCode($code)){
                return $this->response->sendError(ResponseMessage::ZONE_CODE_EXISTS);
            }
            return $this->response->sendError();
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone != false){
            $zone->edit($name, $code, $description);
            if ($zone->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function changeStatusAction(){
        $this->auth->allowOnly([Role::ADMIN]);

        $zone_id = $this->request->getPost('zone_id');
        $status = $this->request->getPost('status');

        if (in_array(null, [$zone_id, $status])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone != false){
            $zone->changeStatus($status);
            if ($zone->save()){
                return $this->response->sendSuccess();
            }
            return $this->response->sendError();
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function fetchByIdAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $zone_id = $this->request->getQuery('zone_id');
        if (in_array(null, [$zone_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchById($zone_id);
        if ($zone != false){
            return $this->response->sendSuccess($zone->getData());
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function fetchByCodeAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $code = $this->request->getQuery('code');
        if (in_array(null, [$code])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $zone = Zone::fetchByCode($code);
        if ($zone != false){
            return $this->response->sendSuccess($zone->getData());
        }
        return $this->response->sendError(ResponseMessage::ZONE_DOES_NOT_EXIST);
    }

    public function fetchAllAction(){
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);

        $status = $this->request->getQuery('status');

        $filter_by = [];
        if (!is_null($status)){ $filter_by['status'] = $status; }

        return $this->response->sendSuccess(Zone::fetchAll($offset, $count, $filter_by));
    }
} 