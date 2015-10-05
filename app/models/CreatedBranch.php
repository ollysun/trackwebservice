<?php

/**
 * Class CreatedBranch
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 * @author Olawale Lawal <wale@cottacush.com>
 */
class CreatedBranch extends Branch {
    public function getSource()
    {
        return 'branch';
    }
}