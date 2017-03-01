<?php
use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class InvoiceParcel extends EagerModel
{

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('invoice_parcels');
    }

    /**
     * Add Parcels for an Invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $invoiceNumber
     * @param $parcels
     * @throws Exception
     */
    public static function addParcels($invoiceNumber, $parcels)
    {
        $values = [];

        foreach ($parcels as $parcel) {
            $rowData = [];
            $rowData[] = $invoiceNumber;
            $rowData[] = $parcel->waybill_number;
            $rowData[] = $parcel->discount;
            $rowData[] = $parcel->net_amount;
            $rowData[] = Util::getCurrentDateTime();
            $rowData[] = Util::getCurrentDateTime();
            $values[] = $rowData;
        }

        $batch = new Batch('invoice_parcels');
        $batch->setRows(['invoice_number', 'waybill_number', 'discount', 'net_amount', 'created_at', 'updated_at']);
        $batch->setValues($values);
        $batch->insert();
    }

    /**
     * Validates that a parcel exists
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $parcels
     * @return bool
     */
    public static function validateParcels($parcels)
    {
        $waybillNumbers = [];
        foreach ($parcels as $parcel) {
            $waybillNumbers[] = $parcel->waybill_number;
        }
        // Check if parcels exist
        $foundParcels = Parcel::query()->inWhere('waybill_number', $waybillNumbers)->execute()->toArray();

        return count($foundParcels) == count($parcels);
    }

    /**
     * Validates that no invoice exists for parcels
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $parcels
     * @return bool
     */
    public static function validateInvoiceParcel($parcels)
    {
        $waybillNumbers = [];
        foreach ($parcels as $parcel) {
            $waybillNumbers[] = $parcel->waybill_number;
        }

        $foundInvoiceParcels = InvoiceParcel::query()->inWhere('waybill_number', $waybillNumbers)->execute()->toArray();
        return empty($foundInvoiceParcels);
    }

    /**
     * Fetches paginated list of invoices
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $offset
     * @param $count
     * @param $fetch_with
     * @param $filter_by
     * @return array
     */
    public static function fetchAll($offset, $count, $fetch_with, $filter_by)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from(self::class);

        $columns = [self::class . '.*'];
        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder->columns($columns);

        if(is_null($filter_by['no_paginate'])) {
            $builder->offset($offset)->limit($count);
        }

        $result = $builder->getQuery()->execute();
        $models = [];
        foreach ($result as $data) {
            $model = (property_exists($data, lcfirst(self::class))) ? $data->{lcfirst(self::class)}->toArray() : $data->toArray();

            $relatedRecords = $obj->loadRelatedModels($data, true);
            $model = array_merge($model, $relatedRecords);

            $models[] = $model;
        }

        return $models;
    }

    /**
     * Adds filter params to builder
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param BuilderInterface $builder
     * @param $filter_by
     * @return mixed
     */
    private static function addFetchCriteria($builder, $filter_by)
    {
        if (isset($filter_by['from_created_at'], $filter_by['to_created_at'])) {
            $from = (isset($filter_by['from_created_at'])) ? $filter_by['from_created_at'] . ' 00:00:00' : null;
            $to = (isset($filter_by['to_created_at'])) ? $filter_by['to_created_at'] . ' 23:59:59' : null;
            $builder = Util::betweenDateRange($builder, self::class . '.created_at', $from, $to);
        }

        if (isset($filter_by['status'])) {
            $builder->andWhere(self::class.'.status=:status:', ['status' => $filter_by['status']], ['status' => PDO::PARAM_STR]);
        }

        if (isset($filter_by['invoice_number'])) {
            $builder->andWhere(self::class . '.invoice_number=:invoice_number:', ['invoice_number' => $filter_by['invoice_number']], ['invoice_number' => PDO::PARAM_STR]);
        }

        return $builder;
    }

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap()
    {
        return [
            [
                'field' => 'parcel',
                'model_name' => self::class,
                'ref_model_name' => Parcel::class,
                'foreign_key' => 'waybill_number',
                'reference_key' => 'waybill_number'
            ],
            [
                'field' => 'receiver_address',
                'model_name' => Parcel::class,
                'ref_model_name' => Address::class,
                'foreign_key' => 'receiver_address_id',
                'reference_key' => 'id',
                'alias' => 'ReceiverAddress'
            ],
            [
                'field' => 'receiver_city',
                'model_name' => ReceiverAddress::class,
                'ref_model_name' => City::class,
                'foreign_key' => 'city_id',
                'reference_key' => 'id',
                'alias' => 'ReceiverCity'
            ],
            [
                'field' => 'sender_address',
                'model_name' => Parcel::class,
                'ref_model_name' => Address::class,
                'foreign_key' => 'sender_address_id',
                'reference_key' => 'id',
                'alias' => 'SenderAddress'
            ],
            [
                'field' => 'sender_city',
                'model_name' => SenderAddress::class,
                'ref_model_name' => City::class,
                'foreign_key' => 'city_id',
                'reference_key' => 'id',
                'alias' => 'SenderCity'
            ],
            [
                'field' => 'receiver',
                'model_name' => Parcel::class,
                'ref_model_name' => Receiver::class,
                'foreign_key' => 'receiver_id',
                'reference_key' => 'id',
            ],
            [
                'field' => 'delivery_receipt',
                'model_name' => Parcel::class,
                'ref_model_name' => DeliveryReceipt::class,
                'foreign_key' => 'waybill_number',
                'reference_key' => 'waybill_number',
                'join_type' => 'left'
            ]
        ];
    }
}