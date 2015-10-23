<?php

use Phinx\Migration\AbstractMigration;

class ParcelComment extends AbstractMigration
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function up()
    {
        $upSql = file_get_contents(dirname(__FILE__) . '/../data/parcel/parcel_comments_install.sql');
        $this->execute($upSql);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function down()
    {
        $downSql = "DROP TABLE IF EXISTS parcel_comments";
        $this->execute($downSql);
    }
}
