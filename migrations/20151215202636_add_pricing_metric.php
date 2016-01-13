<?php

use Phinx\Migration\AbstractMigration;

class AddPricingMetric extends AbstractMigration
{
    /**
     * Add a column for the saving of the metric used for the parcel
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function change()
    {
        $this->table('parcel')->addColumn('qty_metrics', 'string', ['limit' => 15])->save();
    }
}
