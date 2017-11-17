<?php

/**
 * Class SenderAddressState
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class SenderAddressState extends State {
    public function getSource()
    {
        return 'state';
    }
} 