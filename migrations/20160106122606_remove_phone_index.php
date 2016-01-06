<?php

use Phinx\Migration\AbstractMigration;

class RemovePhoneIndex extends AbstractMigration
{
    /**
     * Remove the `phone` number index
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function up()
    {
        $this->table('user')->removeIndex(array('phone'));
    }

    public function down()
    {
        echo "This migration cannot be reversed";
    }
}
