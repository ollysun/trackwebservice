<?php

use Phinx\Migration\AbstractMigration;

class BranchCodeAlter extends AbstractMigration
{
    /**
     * Increase the size of code to
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $this->table('branch')->changeColumn('code', 'string', ['limit' => 15])->update();
    }
}
