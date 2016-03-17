<?php

use Phinx\Migration\AbstractMigration;

class InstallCorporateAccountTypeAndAddAccountTypeToCompany extends AbstractMigration
{
    public function change()
    {
        $customer_type = $this->table('corporate_account_type');
        $customer_type->addColumn('code', 'string', ['null' => false, 'limit' => 20]);
        $customer_type->addColumn('acronym', 'string', ['null' => false]);
        $customer_type->addColumn('name', 'string', ['null' => false]);
        $customer_type->addIndex('code', ['unique' => true]);
        $customer_type->create();

        $company = $this->table('company');
        $company->addColumn('account_type_id', 'integer',['default' => null]);
        $company->update();
    }
}
