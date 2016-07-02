<?php

use Phinx\Migration\AbstractMigration;

class AulterAuditAddParameters extends AbstractMigration
{
    public function up()
    {
        $this->table('audit')
            ->addColumn('parameters', 'string', ['length' => 500, 'null' => true, 'default' => null, 'after' => 'browser'])
            ->update();
    }

    public function down()
    {
        $this->table('audit')
            ->removeColumn('parameters')
            ->update();
    }
}
