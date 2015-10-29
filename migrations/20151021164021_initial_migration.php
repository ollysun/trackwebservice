<?php
use Phinx\Migration\AbstractMigration;

/**
 * Class InitialMigration
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class InitialMigration extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('user_auth')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/install.sql');
            $this->execute($install_sql);
        }
    }
}
