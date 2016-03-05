<?php
use Job as JobLog;
use Pheanstalk\Job;

/**
 * Class BaseJob
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
abstract class BaseJob
{
    /** @var  $worker BaseWorker */
    public $worker;
    public $data;
    public $id;
    /** @var $jobLog JobLog */
    public $jobLog;

    public function __construct(Job $serverJob)
    {
        $this->data = json_decode($serverJob->getData());
        $this->id = $serverJob->getId();
    }

    public function __toString()
    {
        return '';
    }

    /**
     * action to perform on start
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onStart()
    {
        $connection = (new JobLog())->getReadConnection();
        $connection->close();
        $connection->connect();

        $logs = JobLog::find(['conditions' => 'server_job_id=:id: AND queue=:queue:', 'bind' => ['id' => $this->id, 'queue' => $this->worker->queue, 'status' => JobLog::STATUS_QUEUED]]);
        if (!$logs) {
            return false;
        }

        $jobLog = $logs->getLast();
        if (!$jobLog) {
            return false;
        }
        $this->jobLog = $jobLog;
        $this->jobLog->started_at = Util::getCurrentDateTime();
        $this->jobLog->status = JobLog::STATUS_IN_PROGRESS;
        return $this->jobLog->save();
    }

    /**
     * action to perform on fail
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param null $error
     * @return mixed
     */
    public function onFail($error = null)
    {
        if (!$this->jobLog) {
            return false;
        }
        $this->jobLog->status = JobLog::STATUS_FAILED;
        $this->jobLog->error_message = $error;
        return $this->jobLog->save();
    }

    /**
     * action to perform on success
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onSuccess()
    {
        if (!$this->jobLog) {
            return false;
        }
        $this->jobLog->status = JobLog::STATUS_SUCCESS;
        return $this->jobLog->save();
    }

    /**
     * action to perform on complete
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onComplete()
    {
        if (!$this->jobLog) {
            return false;
        }
        $this->jobLog->completed_at = Util::getCurrentDateTime();
        return $this->jobLog->save();
    }

    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public abstract function execute();

    /**
     * set Job worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $worker
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    }


}