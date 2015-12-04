<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class AddDraftParcelSorting
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class AddDraftParcelSorting extends AbstractMigration
{

    /**
     * Create parcel draft sorting tables
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $this->table('parcel_draft_sorts')
            ->addColumn('waybill_number', 'string', ['limit' => 25, 'null' => false])
            ->addColumn('sort_number', 'string', ['limit' => 25, 'null' => false])
            ->addColumn('to_branch', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('created_by', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addIndex('waybill_number', ['name' => 'k_parcel_draft_sorts_waybill_number'])
            ->addIndex('created_by', ['name' => 'k_parcel_draft_sorts_created_by'])
            ->addIndex('to_branch', ['name' => 'k_parcel_draft_sorts_to_branch'])
            ->addIndex('sort_number', ['name' => 'u_parcel_draft_sorts_sort_number', 'unique' => true])
            ->addForeignKey('to_branch', 'branch', 'id', ['constraint' => 'fk_parcel_draft_sorts_branch_to_branch'])
            ->addForeignKey('created_by', 'admin', 'id', ['constraint' => 'fk_parcel_draft_sorts_admin_created_by'])
            ->create();

        $this->table('draft_bag_parcels')
            ->addColumn('bag_sort_number', 'string', ['limit' => 25, 'null' => false])
            ->addColumn('parcel_sort_number', 'string', ['limit' => 25, 'null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addIndex('bag_sort_number', ['name' => 'k_draft_bag_parcels_bag_sort_number'])
            ->addIndex('parcel_sort_number', ['name' => 'k_draft_bag_parcels_parcel_sort_number'])
            ->addForeignKey('parcel_sort_number', 'parcel_draft_sorts', 'sort_number', ['constraint' => 'fk_dbp_pds_parcel_sort_number'])
            ->addForeignKey('bag_sort_number', 'parcel_draft_sorts', 'sort_number', ['constraint' => 'fk_dbp_pds_bag_sort_number'])
            ->create();
    }
}
