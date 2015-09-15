<?php
use Phalcon\Cli\Task;


/**
 * Class BranchUploadTask
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BranchUploadTask extends Task
{

    public function mainAction($params)
    {
        $handle = fopen(dirname(__FILE__) . "/../../data/hubs.csv", "r");

        if (isset($params[0])) {

            if ($handle) {
                /** @var BranchType $branchType */
                $branchType = BranchType::findFirstByName($params[0]);
                if ($branchType) {
                    while (($line = fgets($handle)) !== false) {
                        $this->uploadBranch($line, $branchType->getId());
                    }
                    fclose($handle);
                }
            } else {
                print "Could not read hub file";
            }
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $line
     * @param $branchTypeId
     * @return bool
     */
    private function uploadBranch($line, $branchTypeId)
    {
        $data = str_getcsv($line);

        if (count($data) == 3) {
            $name = trim($data[0], '"');

            $stateName = strtolower(trim($data[1], '"'));
            /** @var State $state */
            $state = State::findFirstByName($stateName);
            if (!$state) {
                print "Could not upload " . $name . " branch Reason: Could get state: " . $stateName . "\n";
                return false;
            }

            $address = trim($data[2], '"');


            $branch = new Branch();
            $branch->setAddress($address);
            $branch->setBranchType($branchTypeId);
            $branch->setName($name);
            $branch->setCreatedDate($this->getCurrentDateTime());
            $branch->setModifiedDate($this->getCurrentDateTime());
            $branch->setStatus(Status::ACTIVE);
            $branch->setStateId($state->getId());
            $branch->setCode(uniqid());
            if (!$branch->saveBranch()) {
                print "Could not upload " . $branch->getName() . " branch";
                print " Reason : ";
                $messages = $branch->getMessages();
                foreach ($messages as $message) {
                    print $message . ", ";
                }
                print "\n";
                return false;
            }
            print "Uploaded " . $branch->getName() . " branch" . "\n";
            return true;
        } else {
            print "Could not upload branch Reason: invalid data: $line \n";
            return false;
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool|string
     */
    private function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s');

    }

}