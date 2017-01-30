<?php

use Phinx\Migration\AbstractMigration;

class PaymentAdviceTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('payment_advice')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/payment_advice.sql');
            $this->execute($install_sql);
        }
    }
}
