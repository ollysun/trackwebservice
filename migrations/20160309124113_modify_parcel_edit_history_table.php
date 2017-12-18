<?php

use Phinx\Migration\AbstractMigration;

class ModifyParcelEditHistoryTable extends AbstractMigration
{
    public function change()
    {
        $this->execute('update parcel_edit_history ph, admin ad set ph.changed_by = ad.id where ph.changed_by = ad.fullname');
        $this->execute('alter table parcel_edit_history modify changed_by integer not null');
        $this->execute('alter table parcel_edit_history add FOREIGN key (changed_by) REFERENCES admin(id)');
        $this->execute('alter table parcel_edit_history add parcel_id bigint not null after id');
        $parcels_edit_history = $this->fetchAll("select * from parcel_edit_history");
        foreach ($parcels_edit_history as $parcel_edit_history) {
            $before_data_array = (json_decode($parcel_edit_history['before_data'], true));
            $parcel_id = $before_data_array['id'];
            $this->execute('update parcel_edit_history set parcel_id = ' . $parcel_id . ' where id =' . $parcel_edit_history['id']);
        }
        $this->execute('alter table parcel_edit_history add FOREIGN key (parcel_id) REFERENCES parcel(id)');
    }
}