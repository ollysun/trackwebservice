<?php

/**
 * Class ReceiverAddress
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class ReceiverAddress extends Address {
    public function getSource()
    {
        return 'address';
    }
} 