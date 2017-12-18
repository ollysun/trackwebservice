<?php

/**
 * Class CreatedBranch
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 * @author Abdul-Rahman Shitu <rahman@cottacush.com>
 */
class CreatedBy extends Admin {
    public function getSource()
    {
        return 'admin';
    }
} 