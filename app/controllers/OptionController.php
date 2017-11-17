<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 12/30/2016
 * Time: 7:08 AM
 */
class OptionController extends ControllerBase
{
    public function getAction(){
        $key = $this->request->getQuery('key');
        if($key == null) return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        $option = Option::get($key);
        if(!$option) return $this->response->sendError('Option not found');
        return $this->response->sendSuccess($option);
    }
    public function getAllAction(){
        $keys = $this->request->getQuery('keys');
        $keys = explode(',', $keys);
        $option = Option::getAll($keys);
        if(!$option) return $this->response->sendError('Option not found');
        return $this->response->sendSuccess($option);
    }

    public function saveAllAction(){
        $postData = $this->request->getJsonRawBody(true);
        foreach ($postData as $data) {
            if($data['id'] == 0){
                $option = new Option();
                $option->setDataType($data['data_type']);
                $option->setKey($data['key']);
                $option->setValue($data['value']);
                if(Option::get($data['data_type'])) {
                    continue;
                }
                $option->save();
            }else{
                $option = Option::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $data['id']]]);
                $option->setDataType($data['data_type']);
                $option->setValue($data['value']);
                $option->save();
            }
        }
        return $this->response->sendSuccess();
    }

    public function addAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $key = $this->request->getPost('key');
        $value = $this->request->getPost('value');
        $data_type = $this->request->getPost('data_type');
        if(in_array(null, [$key, $value, $data_type])) return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        if(Option::get($key)) return $this->response->sendError('Key exists');
        $option = new Option();
        $option->setKey($key)->setValue($value)->setDataType($data_type);
        if($option->save()) return $this->response->sendSuccess();
        $this->response->sendError(implode(' ', $option->getMessages()));
    }

    public function updateAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->request->getPost('id');
        $value = $this->request->getPost('value');
        $data_type = $this->request->getPost('data_type');
        if(in_array(null, [$id, $value, $data_type])) return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        $option = Option::findFirst($id);
        $option->setValue($value)->setDataType($data_type);
        if($option->save()) return $this->response->sendSuccess();
        $this->response->sendError(implode(' ', $option->getMessages()));
    }

}