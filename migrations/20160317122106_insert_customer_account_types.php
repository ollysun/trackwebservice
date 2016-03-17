<?php

use Phinx\Migration\AbstractMigration;

class InsertCustomerAccountTypes extends AbstractMigration
{
    public function change()
    {

        $account_types = [['M', 'ECR', 'Ecommerce Merchant on Credit'], ['CR', 'CCC', 'Corporate Credit client'],
            ['CA', 'CSH', 'Cash Client'], ['CAM', 'ECS', 'Ecommerce Merchant on Cash']];
        $this->table('corporate_account_type')->insert(['code', 'acronym', 'name'], $account_types)->update();
    }
}
