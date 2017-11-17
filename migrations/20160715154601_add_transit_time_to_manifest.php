<?php

use Phinx\Migration\AbstractMigration;

class AddTransitTimeToManifest extends AbstractMigration
{
    public function up()
    {
        $this->table('manifest')
            ->addColumn('transit_time', 'integer', ['null' => true, 'default' => 0, 'after' => 'status'])
            ->update();
    }

    public function down()
    {
        $this->table('manifest')
            ->removeColumn('transit_time')
            ->update();
    }
}
