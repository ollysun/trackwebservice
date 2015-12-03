<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class Invoice extends BaseModel
{

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('invoices');
    }

    /**
     * Generates an invoice
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $data
     * @return bool
     */
    public static function generate($data)
    {
        return self::add($data);
    }

    /**
     * Generates the invoice number
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return string
     */
    public static function generateInvoiceNumber()
    {
        $invoiceNumber = 'TP-' . date('Ymd-His') . '-' . rand(0, 10);
        return $invoiceNumber;
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
            ->from('Invoice');

        $columns = ['Invoice.*'];

        $builder->columns($columns)->offset($offset)->limit($count);

        $result = $builder->getQuery()->execute();
        $invoices = [];
        foreach ($result as $data) {
            $invoices[] = $data->toArray();
        }

        return $invoices;
    }



    /**
     * Gets the total count
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $filter_by
     */
    public static function getTotalCount($filter_by)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()->from('Invoice');
        $columns = ['COUNT(*) as count'];
        $builder = self::addFetchCriteria($builder, $filter_by);
        $count = $builder->columns($columns)->getQuery()->getSingleResult();
        return $count['count'];
    }

    /**
     * Adds filter params to builder
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $builder
     * @param $filter_by
     * @return mixed
     */
    private static function addFetchCriteria($builder, $filter_by)
    {
        return $builder;
    }
}