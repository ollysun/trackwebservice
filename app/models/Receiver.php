<?php

/**
 * Class Receiver
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class Receiver extends User {
    public function getSource()
    {
        return 'user';
    }
} 