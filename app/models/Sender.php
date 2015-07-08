<?php

/**
 * Class Sender
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class Sender extends User {
    public function getSource()
    {
        return 'user';
    }
} 