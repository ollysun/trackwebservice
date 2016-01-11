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
}