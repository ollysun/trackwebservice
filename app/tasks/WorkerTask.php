<?php

/**
 * Class WorkerTask
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class WorkerTask extends BaseTask
{
    /**
     * Start Worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $args
     */
    public function startAction($args)
    {
        $workerName = $this->getWorkerName($args);
        $this->printToConsole('Starting ' . $workerName . '...');
        /** @var BaseWorker $workerInstance */
        $workerInstance = $this->getWorkerInstance($workerName);
        $this->printToConsole($workerName . ' Started!');
        $workerInstance->start();
    }

    /**
     * Stop Worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $args
     */
    public function stopAction($args)
    {
        $workerName = $this->getWorkerName($args);
        $this->printToConsole('Stopping ' . $workerName . '...');
        /** @var BaseWorker $workerInstance */
        $workerInstance = $this->getWorkerInstance($workerName);
        $workerInstance->stop();
        $this->printToConsole($workerName . ' Stopped!');
    }

    /**
     * Restart Worker
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $args
     */
    public function restartAction($args)
    {
        $workerName = $this->getWorkerName($args);
        $this->printToConsole('Restarting ' . $workerName . '...');
        $workerInstance = $this->getWorkerInstance($workerName);
        $this->printToConsole('Stopping ' . $workerName . '...');
        $workerInstance->stop();
        $this->printToConsole($workerName . ' Stopped!');
        $this->printToConsole('Restarted ' . $workerName);
        $workerInstance->start();
    }

    /**
     * Get worker Instance
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $worker
     * @return BaseWorker
     */
    private function getWorkerInstance($worker)
    {
        $workerInstance = new $worker;
        if (is_null($workerInstance)) {
            $this->printToConsole('Could not instantiate worker ' . $worker);
            exit;
        }

        if (!($workerInstance instanceof BaseWorker)) {
            $this->printToConsole('Invalid worker supplied!. Please supply a valid worker');
            exit;
        }
        return $workerInstance;
    }

    /**
     * Get worker name
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $args
     * @return mixed
     */
    private function getWorkerName($args)
    {
        if (!is_array($args) || !$args) {
            $this->printToConsole('Please provide a worker name');
            exit;
        }

        return $args[0];
    }
}