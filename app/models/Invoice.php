<?php
use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class Invoice extends EagerModel
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
        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder = self::addFetchCriteria($builder, $filter_by);
        $builder->columns($columns)->offset($offset)->limit($count);

        $result = $builder->getQuery()->execute();
        $invoices = [];
        foreach ($result as $data) {
            $invoice = (property_exists($data, 'invoice')) ? $data->invoice->toArray() : $data->toArray();

            $relatedRecords = $obj->loadRelatedModels($data, true);
            $invoice = array_merge($invoice, $relatedRecords);

            $invoices[] = $invoice;
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
     * @param BuilderInterface $builder
     * @param $filter_by
     * @return mixed
     */
    private static function addFetchCriteria($builder, $filter_by)
    {
        if (isset($filter_by['status'])) {
            $builder->andWhere('Invoice.status=:status:', ['status' => $filter_by['status']], ['status' => PDO::PARAM_STR]);
        }

        if (isset($filter_by['company_id'])) {
            $builder->andWhere('Invoice.company_id=:company_id:', ['company_id' => $filter_by['company_id']], ['company_id' => PDO::PARAM_INT]);
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
                'field' => 'company',
                'model_name' => 'Invoice',
                'ref_model_name' => 'Company',
                'foreign_key' => 'company_id',
                'reference_key' => 'id'
            ]
        ];
    }
}