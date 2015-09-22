<?php

/**
 * Class StateUploadTask
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class StateUploadTask extends BaseTask
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function mainAction()
    {
        $dataFileName = 'cities.csv';
        $handle = fopen(dirname(__FILE__) . "/../../data/$dataFileName", "r");

        if (!$handle) {
            $this->printToConsole("Unable to read data file");
            exit;
        }

        while (($line = fgets($handle)) !== false) {
            $this->updateStateCode($line);
        }
        fclose($handle);

    }


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $line
     * @return bool
     */
    private function updateStateCode($line)
    {
        $data = str_getcsv($line);
        $state_name = strtolower(trim($data[1]));
        $state_code = strtoupper(trim($data[2]));

        /** @var State $state */
        $state = State::findFirstByName($state_name);
        if (!$state) {
            $this->printToConsole("Could not update state code for $state_name to $state_code. Reason: Could not find $state_name state");
            return false;
        }

        $state->code = $state_code;

        if (!$state->save()) {
            $this->printToConsole("Could not update state code for $state_name to $state_code. Reason:" . $this->printStringArray($state->getMessages()));
            return false;
        } else {
            $this->printToConsole("Updated state code for $state_name to $state_code");
            return true;
        }
    }
}