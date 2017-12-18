<?php

/**
 * Class SenderAddress
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class SenderAddress extends Address {
    public function getSource()
    {
        return 'address';
    }
} 