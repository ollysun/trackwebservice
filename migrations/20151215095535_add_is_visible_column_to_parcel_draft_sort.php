<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class AddIsVisibleColumnToParcelDraftSort
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class AddIsVisibleColumnToParcelDraftSort extends AbstractMigration
{
    /**
     * Add is visible column
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $this->table('parcel_draft_sorts')->addColumn('is_visible', 'integer', ['limit' => 1, 'default' => 1, 'null' => false])->update();
    }
}
