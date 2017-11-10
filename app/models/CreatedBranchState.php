<?php

/**
 * Class CreatedBranchState
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 * @author Olawale Lawal <wale@cottacush.com>
 */
class CreatedBranchState extends State {
    public function getSource()
    {
        return 'state';
    }
}