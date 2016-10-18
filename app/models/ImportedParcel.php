<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 10/14/2016
 * Time: 7:57 AM
 */
class ImportedParcel extends \Phalcon\Mvc\Model
{
    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'tracking_number' => $this->getTrackingNumber(),
            'local_waybill_number' => $this->getLocalWaybillNumber(),
            'last_status' => $this->getLastStatus(),
            'last_status_date' => $this->getLastStatusDate()
        );
    }

    public function initData($data)
    {
        $this->setTrackingNumber($data['tracking_number']);
        $this->setLocalWaybillNumber($data['local_waybill_number']);
        $this->setLastStatus($data['last_status']);
        $this->setLastStatusDate($data['last_status_date']);
    }
    /**
     * @return array
     */
    public function getHistories(){
        return ImportedParcelHistory::fetchAll(0, 0, ['imported_parcel_id' => $this->getId()]);
    }

    public function getSource()
    {
        return 'imported_parcels';
    }

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $tracking_number;

    /**
     *
     * @var string
     */
    protected $local_waybill_number;

    /**
     *
     * @var string
     */
    protected $last_status;

    /**
     *
     * @var string
     */
    protected $last_status_date;

    public function setTrackingNumber($tracking_number)
    {
        $this->tracking_number = $tracking_number;
        return $this;
    }

    public function setLocalWaybillNumber($local_waybill_number)
    {
        $this->local_waybill_number = $local_waybill_number;
        return $this;
    }

    public function setLastStatus($last_status)
    {
        $this->last_status = $last_status;
        return $this;
    }

    public function setLastStatusDate($last_status_date)
    {
        $this->last_status_date = $last_status_date;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTrackingNumber()
    {
        return $this->tracking_number;
    }

    public function getLocalWaybillNumber()
    {
        return $this->local_waybill_number;
    }

    public function getLastStatus()
    {
        return $this->last_status;
    }

    public function getLastStatusDate()
    {
        return $this->last_status_date;
    }

}