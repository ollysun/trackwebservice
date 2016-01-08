<?php

/**
 * Author: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 08/01/2016
 * Time: 2:29 PM
 */
class BulkParcelCreationJob extends BaseJob
{
    /**
     * action to perform on start
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function onStart()
    {

    }

    /**
     * execute current job
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function execute()
    {
        echo $this->data;
        $jobData = json_decode($this->data);

//        $shipmentData = $jobData->data;
//        $company_id = $jobData->compan
    }
}