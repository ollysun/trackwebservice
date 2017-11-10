<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class MigrateAdminTask extends BaseTask
{
    /**
     * Main Action
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function mainAction()
    {
        // Get all admins - NB - This is ok as of the time of writing this code, only 31 admins
        // If to be used for a later time, batch get admins
        $admins = Admin::find();

        foreach ($admins as $admin) {
            $this->db->begin();
            // Create row in user_auth table
            $this->printToConsole("Migrating user with id {$admin->getId()}");
            $userAuth = new UserAuth();

            $userAuth->email = $admin->email;
            $userAuth->password = $admin->password;
            $userAuth->status = $admin->status;
            $userAuth->entity_type = 1;
            $userAuth->created_date = Util::getCurrentDateTime();
            $userAuth->modified_date = Util::getCurrentDateTime();

            if ($userAuth->save()) {
                $this->printToConsole("Created user_auth_row for user with id {$admin->getId()}");
                $userAuth->refresh();
                $admin->setUserAuthId($userAuth->getId());

                if ($admin->save()) {
                    $this->db->commit();
                    $this->printToConsole("Successfully migrated user with id {$admin->getId()}");
                }
            }
        }
    }
}