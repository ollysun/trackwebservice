<?php

/**
 * Class SecondaryContact
 * - created to help querying using PHQL
 * - Once Phalcon has been upgrade to > 2.0.3, this class should be reviewed
 */
class SecondaryContact extends CompanyUser {
    public function getSource()
    {
        return 'company_user';
    }
} 