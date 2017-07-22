<?php

class SettingController extends ControllerBase {

  function saveAction() {
    $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
    $data = $this->request->getPost();

    $setting = Setting::findFirst([
      'name = :name:',
      'bind' => ['name' => $data['setting_name']]
    ]);

    if (!$setting) {
      $setting = new Setting();
    }

    $value = [
      'limit_percentage' => $data['limit_percentage'],
      'email_subject' => $data['email_subject'],
      'email_body' => $data['email_body'],
      'alert_emails' => $data['alert_emails'],
      'send_to_client' => $data['send_to_client']
    ];

    $setting->setName($data['setting_name']);
    $setting->setValue($value);

    try {
      $setting->save();
      return $this->response->sendSuccess($setting);
    } catch (Exception $e) {

      return $this->response->sendError('Unable to save setting');
    }

  }

  function getAction() {
    $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
    $setting_name = $this->request->getQuery('setting_name');

    $setting = Setting::findFirst(['name = :name:',
      'bind' => ['name' => $setting_name]
    ]);

    if($setting) {
      return $this->response->sendSuccess($setting);
    }

    return $this->response->sendError('Setting Not Found!');
  }
}