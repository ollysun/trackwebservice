<?php

class ExportAgentController extends ControllerBase {

    public function getAllAction(){
        $this->auth->allowOnly(Role::ADMIN);

        $agents = ExportAgent::find()->toArray();
        //if($with_staff) $fetch_with['with_staff'] = true;

        return $this->response->sendSuccess($agents);
    }
}