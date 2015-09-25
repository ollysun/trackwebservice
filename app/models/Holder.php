<?php

/**
 * Class Holder
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class Holder extends Admin {
    public function getSource()
    {
        return 'admin';
    }
} 