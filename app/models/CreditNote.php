<?php
use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CreditNote extends EagerModel
{
    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('credit_notes');
    }

    /**
     * Generates credit note number using microtime with prefix
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return string
     */
    public static function generateCreditNoteNumber()
    {
        $time = microtime(true) * 10000;
        return 'CN-' . $time;
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
        $builder->columns($columns)->offset($offset)->limit($count);

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
        if (isset($filter_by['from_created_at']) || $filter_by['to_created_at']) {
            $from = (isset($filter_by['from_created_at'])) ? $filter_by['from_created_at'] . ' 00:00:00' : null;
            $to = (isset($filter_by['to_created_at'])) ? $filter_by['to_created_at'] . ' 23:59:59' : null;
            $builder = Util::betweenDateRange($builder, self::class . '.created_at', $from, $to);
        }

        if (isset($filter_by['status'])) {
            $builder->andWhere(self::class . '.status=:status:', ['status' => $filter_by['status']], ['status' => PDO::PARAM_STR]);
        }
        if (isset($filter_by['company_id'])) {
            $builder->andWhere(Invoice::class . '.company_id=:company_id:', ['company_id' => $filter_by['company_id']], ['company_id' => PDO::PARAM_INT]);
        }

        return $builder;
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
        /**
         * @var $builder BuilderInterface
         */
        $builder = $obj->getModelsManager()->createBuilder()->from(self::class);
        $columns = ['COUNT(*) as count'];
        $builder = self::addFetchCriteria($builder, $filter_by);
        if (isset($filter_by['company_id'])) {
            $builder->join('Invoice', 'Invoice.invoice_number = CreditNote.invoice_number');
        }
        $count = $builder->columns($columns)->getQuery()->getSingleResult();
        return $count['count'];
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
                'field' => 'invoice',
                'model_name' => self::class,
                'ref_model_name' => 'Invoice',
                'foreign_key' => 'invoice_number',
                'reference_key' => 'invoice_number'
            ],
            [
                'field' => 'company',
                'model_name' => Invoice::class,
                'ref_model_name' => Company::class,
                'foreign_key' => 'company_id',
                'reference_key' => 'id'
            ]
        ];
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $creditNoteNo
     * @return array
     */
    public function getPrintoutFields($creditNoteNo)
    {
        $builder = CreditNote::getModelsManager()->createBuilder();
        $builder->from('CreditNote');
        $builder->where('credit_note_number = :credit_note_no:');
        $builder->columns('CreditNote.*,Invoice.*,Company.name');
        $builder->innerJoin('Invoice','Invoice.invoice_number = CreditNote.invoice_number');
        $builder->innerJoin('Company','Invoice.company_id = Company.id');
        $bind['credit_note_no'] = $creditNoteNo;
        $printOutDetails = $builder->getQuery()->execute($bind);
        return $printOutDetails->toArray();
    }
}