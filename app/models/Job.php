<?php

/**
 * Class Job
 * @property int server_job_id
 * @property  string job_data
 * @property  int created_by
 * @property  string status
 * @property int id
 * @property string created_at
 * @property string started_at
 * @property string completed_at
 * @property string error_message
 * @property  string queue
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class Job extends BaseModel
{

    const STATUS_QUEUED = 'queued';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('jobs');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $queue
     * @param $offset
     * @param $count
     * @return mixed
     */
    public static function fetchAll($queue, $offset, $count)
    {
        $obj = new self();
        $columns = ['Job.*'];
        $builder = $obj->getModelsManager()->createBuilder()->from('Job');
        $builder->where('queue = :queue:', ['queue' => $queue]);
        $builder->columns($columns)->offset($offset)->limit($count);
        $builder->orderBy('Job.created_at DESC');
        return $builder->getQuery()->execute();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $queue
     * @return mixed
     */
    public static function getTotalCount($queue)
    {
        $obj = new self();
        $columns = ['COUNT(*) as count'];
        $builder = $obj->getModelsManager()->createBuilder()->from('Job');
        $builder->where('queue = :queue:', ['queue' => $queue]);
        $count = $builder->columns($columns)->getQuery()->getSingleResult();
        return $count['count'];
    }
}