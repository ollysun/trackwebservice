<?php

/**
 * Class ToCity
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class ReceiverAddressCity extends City {
    public function getSource()
    {
        return 'city';
    }
} 