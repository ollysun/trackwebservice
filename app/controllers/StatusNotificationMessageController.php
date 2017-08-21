<?php


class  StatusNotificationMessageController extends ControllerBase {

  function saveStatusNotificationAction()
  {
      $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
      $data = $this->request->getPost();

      if (isset($data["task"])) {
          $message = StatusNotificationMessage::findFirst([
              'status_id = :status_id:',
              'bind' => ['status_id' => $data['status_id']]
          ]);

          if (!$message) {
              $insertNewRecord = new StatusNotificationMessage();
              $insertNewRecord->setStatusId($data["status_id"]);
              $insertNewRecord->setTextMessage($data["textdata"]);
              $insertNewRecord->setEmailMessage($data["emaildata"]);
              $insertNewRecord->setSubject($data["subject"]);
              try {
                  $insertNewRecord->save();
              } catch (Exception $e) {
                  return $this->response->sendError($insertNewRecord);
              }
          }

          if (!empty($data["textdata"])) {
              $message->setTextMessage($data["textdata"]);
          }

          if (!empty($data["emaildata"])) {
              $message->setEmailMessage($data["emaildata"]);
              $message->setSubject($data["subject"]);
          }

          try {
              $message->save();
              return $this->response->sendSuccess($message->toArray());
          } catch (Exception $e) {
              return $this->response->sendError($e->getMessage());
          }
      }
  }

      function getStatusAction()
      {
          $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);

          $status_data= Status::fetchAll();

          if ($status_data != false) {
              return $this->response->sendSuccess($status_data);
          }
          return $this->response->sendError(ResponseMessage::RECORD_DOES_NOT_EXIST);


      }
}