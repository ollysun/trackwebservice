<?php
use Phalcon\Di;
use Phalcon\Mvc\Model;

/**
 * Class BaseModel
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BaseModel extends Model
{

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     */
    public function initialize()
    {
        if(!Di::getDefault()->getConfig()->isCli) {
            $this->addBehavior(new AuditTrailBehavior());
            $this->keepSnapshots(true);
        }
    }

     /**
     * Set created_at before validation
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnCreate()
    {
        $this->created_at = Util::getCurrentDateTime();
        $this->updated_at = Util::getCurrentDateTime();
    }

    /**
     * Set created_at before validation
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = Util::getCurrentDateTime();
    }

    /**
     * Save data to model
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $called_class = get_called_class();
        $model = new $called_class();
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }
        $status = $model->save();
        return $status;
    }


    /**
     * Get total count of data set
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $filter_by
     * @return null
     */
    public static function getTotalCount($filter_by)
    {
        $called_class = get_called_class();
        $model = new $called_class();
        $builder = $model->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS total_count')
            ->from($called_class);

        $filter_cond = $model::getFilterConditions($filter_by);
        $where = $filter_cond['where'];
        $bind = $filter_cond['bind'];

        $builder->where(join(' AND ', $where));
        $count = $builder->getQuery()->getSingleResult($bind);
        return !is_null($count) ? $count['total_count'] : null;
    }

    /**
     * Get filter conditions
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $filter_by
     * @return array
     */
    public static function getFilterConditions($filter_by = [])
    {
        $bind = [];
        $where = [];
        foreach ($filter_by as $filter => $value) {
            if (isset($value)) {
                $filter_placeholder = str_replace('.', '_', strtolower($filter));
                $where[] = "$filter = :$filter_placeholder:";
                $bind[$filter_placeholder] = $value;
            }
        }
        return ['where' => $where, 'bind' => $bind];
    }

}
