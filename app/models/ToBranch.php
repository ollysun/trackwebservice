<?php

/**
 * Class ToBranch
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class ToBranch extends Branch {
    public function getSource(){
        return 'branch';
    }
} 