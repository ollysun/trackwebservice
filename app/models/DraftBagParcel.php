<?php
use Phalcon\Mvc\Model;

/**
 * Class DraftBagParcel
 * @property string bag_sort_number
 * @property string parcel_sort_number
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class DraftBagParcel extends BaseModel
{

    /**
     * @inheritdoc
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return string
     */
    public function getSource()
    {
        return 'draft_bag_parcels';
    }

    public function initialize()
    {
        $this->belongsTo('parcel_sort_number', ParcelDraftSort::class, 'sort_number');
    }
}