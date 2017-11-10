<?php

class ExportedParcelTrackingController extends ControllerBase {


    public function addAction()
    {
        $exportedparcel_id = $this->request->getPost('exportedparcel_id');
        $admin_id = $this->auth->getPersonId();
        $comment = $this->request->getPost('comment');
        $commentdate = $this->request->getPost('commentdate');
        var_dump([$exportedparcel_id, $admin_id, $comment, $commentdate]);
       // return $this->response->sendSuccess($this->request->getPost());
        if(in_array(null,[$exportedparcel_id, $admin_id, $comment, $commentdate])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        $expotedParcelTracking = new ExportedParcelTracking();
        $expotedParcelTracking->setExportedparcelId($exportedparcel_id);
        $expotedParcelTracking->setAdminId($admin_id);
        $expotedParcelTracking->setComment($comment);
        $expotedParcelTracking->setCommentdate($commentdate);
        if($expotedParcelTracking->save())return $this->response->sendSuccess();
        return $this->response->sendError();


    }
}