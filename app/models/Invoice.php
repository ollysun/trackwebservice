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

    public static function deleteInvoice($number){
        $invoice = Invoice::findFirst(['invoice_number = :invoice_number:',
        'bind' => [
            'invoice_number' => $number,
        ]]);
        if($invoice){
            $invoice->delete();
        }
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
     * @param $company_id
     * @return string
     */
    public static function generateInvoiceNumber($company_id = '')
    {
        $invoiceNumber = 'IN-' . date('Ymd-His') . '-' . $company_id . rand(0, 10);
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
     * @return integer
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
        if (isset($filter_by['from_created_at']) || $filter_by['to_created_at']) {
            $from = (isset($filter_by['from_created_at'])) ? $filter_by['from_created_at'] . ' 00:00:00' : null;
            $to = (isset($filter_by['to_created_at'])) ? $filter_by['to_created_at'] . ' 23:59:59' : null;
            $builder = Util::betweenDateRange($builder, 'Invoice.created_at', $from, $to);
        }

        if (isset($filter_by['status'])) {
            $builder->andWhere('Invoice.status=:status:', ['status' => $filter_by['status']], ['status' => PDO::PARAM_STR]);
        }

        if (isset($filter_by['company_id'])) {
            $builder->andWhere('Invoice.company_id=:company_id:', ['company_id' => $filter_by['company_id']], ['company_id' => PDO::PARAM_INT]);
        }

        return $builder;
    }

    /**
     * Fetches the details of an invoice together with optional related records
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $filter_by
     * @param $fetch_with
     * @return array|bool
     */
    public static function fetchOne($filter_by, $fetch_with)
    {
        $obj = new self();
        $builder = $obj->getModelsManager()->createBuilder()
            ->from(self::class);

        $columns = [self::class . '.*'];
        $bind = [];
        $where = [];

        if (isset($filter_by['id'])) {
            $where[] = self::class . '.id = :id:';
            $bind['id'] = $filter_by['id'];
        }

        if (isset($filter_by['invoice_number'])) {
            $where[] = self::class . '.invoice_number = :invoice_number:';
            $bind['invoice_number'] = $filter_by['invoice_number'];
        }

        $obj->setFetchWith($fetch_with)
            ->joinWith($builder, $columns);

        $builder->columns($columns);
        $builder->where(join(' AND ', $where));

        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return false;
        }

        if (isset($data[0]->company)) {
            $model = $data[0]->{lcfirst(self::class)}->toArray();
            $relatedRecords = $obj->loadRelatedModels($data);

            $model = array_merge($model, $relatedRecords);
        } else {
            $model = $data[0]->toArray();
        }

        return $model;
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
            ],
            [
                'field' => 'credit_note',
                'model_name' => self::class,
                'ref_model_name' => CreditNote::class,
                'foreign_key' => 'invoice_number',
                'reference_key' => 'invoice_number',
                'join_type' => 'left'
            ]
        ];
    }
}