<?php

/**
 * User: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 9/16/15
 * Time: 10:25 AM
 */
class CityUploadTask extends BaseTask
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
            $this->uploadCity($line);
        }
        fclose($handle);

    }


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $line
     * @return bool
     */
    private function uploadCity($line)
    {
        $data = str_getcsv($line);

        if (count($data) != 6) {
            $this->printToConsole("Invalid data: $line");
            return false;
        }

        $name = $data[0];
        $state_name = strtolower(trim($data[1]));
        $transit_time = strtolower(trim($data[3], "+"));
        $onforwarding_charge_name = strtolower(trim($data[4]));
        $hub_name = strtolower(trim($data[5]));

        /** @var State $state */
        $state = State::findFirstByName($state_name);
        if (!$state) {
            $this->printToConsole("Could not upload $name Reason: Could not find $state_name state");
            return false;
        }

        /** @var OnforwardingCharge $onforwarding_charge */
        $onforwarding_charge = OnforwardingCharge::findFirstByName($onforwarding_charge_name);
        if (!$onforwarding_charge) {
            $this->printToConsole("Could not upload $name Reason: Could not find $onforwarding_charge_name onforwarding charge");
            return false;
        }


        if(strlen($hub_name) == 0){
            $this->printToConsole("No hub mapped to city $name. Skipping...");
            return false;
        }

        /** @var Branch $hub */
        $hub = Branch::findFirstByName($hub_name);
        if (!$hub) {
            $this->printToConsole("Could not upload $name Reason: Could not find $hub_name");
            return false;
        }

        if(City::findFirstByName($name)){
            $this->printToConsole("$name already uploaded");
            return false;
        }

        // save city
        $city = new City();
        $city->setStateId($state->getId());
        $city->setStatus(Status::ACTIVE);
        $city->setBranchId($hub->getId());
        $city->setName($name);
        $city->setOnforwardingChargeId($onforwarding_charge->getId());
        $city->setCreatedDate($this->getCurrentDateTime());
        $city->setModifiedDate($this->getCurrentDateTime());
        $city->setTransitTime($transit_time);

        if (!$city->save()) {
            $this->printToConsole("Could not upload $name Reason: " . $this->printStringArray($city->getMessages(), true));
            return false;
        }

        $this->printToConsole("Uploaded $name Successfully");
        return true;

    }
}