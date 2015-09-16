<?php use Phalcon\Cli\Task;

;

/**
 * Class BranchUploadTask
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BranchUploadTask extends BaseTask
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $params
     */
    public function mainAction($params)
    {
        if (!isset($params[0])) {
            $this->printToConsole("Invalid branch type");
            return;
        }

        if (!isset($params[1])) {
            $this->printToConsole("Invalid data file");
            return;
        }

        $branchTypeName = $params[0];
        $dataFileName = $params[1];

        $handle = fopen(dirname(__FILE__) . "/../../data/$dataFileName", "r");

        if ($handle) {
            /** @var BranchType $branchType */
            $branchType = BranchType::findFirstByName($branchTypeName);
            if ($branchType) {
                while (($line = fgets($handle)) !== false) {
                    $this->uploadBranch($line, $branchType);
                }
                fclose($handle);
            }
        } else {
            $this->printToConsole("Unable to read data file");
        }
    }

    /**
     * Upload branch
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $line
     * @param $branchType BranchType
     * @return bool
     */
    private function uploadBranch($line, $branchType)
    {
        $data = str_getcsv($line);

        if (count($data) < 3) {
            $this->printToConsole("Could not upload branch Reason: invalid data: $line");
            return false;
        }

        $name = trim($data[0], '"');

        $stateName = strtolower(trim($data[1], '"'));
        /** @var State $state */
        $state = State::findFirstByName($stateName);
        if (!$state) {
            $this->printToConsole("Could not upload " . $name . " branch Reason: Could get state: " . $stateName);
            return false;
        }

        $address = trim($data[2], '"');

        if (strlen($address) == 0) {
            $address = ' - ';
        }


        $branch = new Branch();
        $branch->setAddress($address);
        $branch->setBranchType($branchType->getId());
        $branch->setName($name);
        $branch->setCreatedDate($this->getCurrentDateTime());
        $branch->setModifiedDate($this->getCurrentDateTime());
        $branch->setStatus(Status::ACTIVE);
        $branch->setStateId($state->getId());
        $branch->setCode(uniqid());
        if (!$branch->saveBranch()) {
            $this->printToConsole("Could not upload " . $branch->getName() . " branch Reason : ". $this->printStringArray($branch->getMessages(), true));
            return false;
        }

        $this->printToConsole("Uploaded " . $branch->getName() . " branch");

        //handle mapping if branch type is ec
        if (strtolower($branchType->getName()) == 'ec' && isset($data[3])) {
            $parentHubName = strtolower(trim($data[3], '"'));

            //create branch map
            $branchMap = new BranchMap();
            /** @var Branch $parentHub */
            $parentHub = Branch::findFirstByName($parentHubName);
            if (!$parentHub) {
                $this->printToConsole("Cannot map " . $branch->getName() . " to " . $parentHubName . " Reason: Parent hub not found");
            } else {
                $branchMap->setStatus(Status::ACTIVE);
                $branchMap->setChildId($branch->getId());
                $branchMap->setParentId($parentHub->getId());
                if (!$branchMap->save()) {
                    $this->printToConsole("Cannot map " . $branch->getName() . " to " . $parentHubName . " Reason: " . $this->printStringArray($branchMap->getMessages(), true));
                } else {
                    $this->printToConsole("Mapped " . $branch->getName() . " to " . $parentHubName);
                }
            }
        }

        return true;

    }


}