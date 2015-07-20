<?php

/**
 * Class ToBranchState
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class ToBranchState extends State {
    public function getSource()
    {
        return 'state';
    }
} 