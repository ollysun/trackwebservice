<?php
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Pheanstalk\PheanstalkInterface;

/**
 * Class BaseWorker
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
abstract class BaseWorker
{
    const STOP_COMMAND = '--STOP WORKER--';
    const STOPPED_COMMAND = '--WORKER STOPPED--';
    /** @var $server Pheanstalk */
    public $server;
    /** @var $currentJob Job */
    public $currentJob;
    public $queue;
    public $isRunning = false;

    public function __construct($queue)
    {
        $this->server = \Phalcon\Di::getDefault()->getPheanStalkServer();
        $this->queue = $queue;
    }

    /**
     * Start the worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function start()
    {
        $serverJob = $this->server->watch($this->queue)->ignore('default')->reserve(0);
        if ($serverJob && $this->isStopCommand($serverJob)) {
            $this->server->delete($serverJob);
        }
        $this->onStart();

        while (true) {
            $serverJob = $this->server->watch($this->queue)->ignore('default')->reserve();
            if (!$serverJob) {
                continue;
            }
            $this->currentJob = $serverJob;

            if ($serverJob->getData() == self::STOPPED_COMMAND) {
                $this->server->delete($serverJob);
                continue;
            }

            if ($this->isStopCommand($serverJob)) {
                $this->onStop();
                $this->server->delete($serverJob);
                $this->closeQueue();
                break;
            }

            $workerJob = $this->initializeJob();
            $workerJob->setWorker($this);
            $workerJob->onStart();

            try {
                if ($workerJob->execute()) {
                    $workerJob->onSuccess();
                } else {
                    $workerJob->onFail();
                }
            } catch (\Exception $ex) {
                $workerJob->onFail($ex->getMessage());
            }

            $workerJob->onComplete();
            $this->server->delete($serverJob);
        }
    }


    /**
     * On Starting
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onStart()
    {

    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $job Job
     * @return bool
     */
    private function isStopCommand($job)
    {
        return $job->getData() == self::STOP_COMMAND;
    }

    /**
     * On Stopping
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onStop()
    {

    }

    /**
     * Open the queue
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    private function closeQueue()
    {
        $this->server->useTube($this->queue)->put(self::STOPPED_COMMAND);
    }

    /**
     * Check if queue is closed
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    private function isQueueClosed()
    {
        $job = $this->server->watch($this->queue)->ignore('default')->reserve(0);
        return (!$job) ? false : $job->getData() == self::STOPPED_COMMAND;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return BaseJob
     */
    public abstract function initializeJob();

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $jobData
     * @return int
     */
    public function addJob($jobData)
    {
        return $this->server->useTube($this->queue)->put($jobData, PheanstalkInterface::DEFAULT_PRIORITY, 5);
    }

    /**
     * Get currently running Job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return Job
     */
    public function getCurrentJob()
    {
        return $this->currentJob;
    }

    /**
     * Stop the worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function stop()
    {
        if (!$this->isQueueClosed()) {
            $this->server->useTube($this->queue)->put(self::STOP_COMMAND);
        }
    }
}