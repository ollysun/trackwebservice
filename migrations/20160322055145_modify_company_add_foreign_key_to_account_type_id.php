<?php

use Phinx\Migration\AbstractMigration;

class ModifyCompanyAddForeignKeyToAccountTypeId extends AbstractMigration
{
    public function change()
    {
        $companies_account_types = file_get_contents(dirname(__FILE__) . '/../data/corporate/change_corporate_account_type', 'r');
        $companies_account_types = explode("\n", $companies_account_types);
        $file = fopen(dirname(__FILE__) . 'public/upload_customer_account_type.csv', 'a');
        foreach ($companies_account_types as $companies_account_type) {
            $corporates_array = str_getcsv($companies_account_type);
            $company_id = $corporates_array[0];
            $account_type_code = $corporates_array[5];
            $company_name = $corporates_array[1];
            $account_type_code = preg_replace('/\s+/', '', $account_type_code);
            $account_type_code = str_replace("-", "–", $account_type_code);
            $account_type_code = explode("–", $account_type_code);
            $account_type_code = $account_type_code[0];
            $result = $this->execute("UPDATE company cp,corporate_account_type atype SET cp.account_type_id = atype.id WHERE atype.code = '$account_type_code' AND cp.id = $company_id");
            if ($result > 0) {
                fputcsv($file, [$company_name, 'was updated Successfully']);
            } else {
                fputcsv($file, [$company_name, ' was not updated Successfully']);
            }
        }
        $this->execute('UPDATE company cp,corporate_account_type atype SET cp.account_type_id = atype.id WHERE atype.code = "CA" AND cp.account_type_id = 0 ');
        $this->table('company')->addForeignKey('account_type_id', 'corporate_account_type', 'id');
    }
}