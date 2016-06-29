<?php

use Phinx\Migration\AbstractMigration;

class InsertCustomerAccountTypes extends AbstractMigration
{
    public function change()
    {
        $account_types = [
            ['code' => 'M', 'acronym' => 'ECR', 'name' => 'Ecommerce Merchant on Credit'],
            ['code' => 'CR', 'acronym' => 'CCC', 'name' => 'Corporate Credit client'],
            ['code' => 'CA', 'acronym' => 'CSH', 'name' => 'Cash Client'],
            ['code' => 'CAM', 'acronym' => 'ECS', 'name' => 'Ecommerce Merchant on Cash']];
        $this->table('corporate_account_type')->insert($account_types)->update();
    }
}
