<?php

/**
 * Class FromBranchState
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class FromBranchState extends State {
    public function getSource()
    {
        return 'state';
    }
} 