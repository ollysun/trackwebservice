<?php

/**
 * User: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 9/16/15
 * Time: 2:27 PM
 */
class MappingUploadTask extends BaseTask
{

    public function mainAction()
    {
        $dataFileName = 'mapping.csv';
        $handle = fopen(dirname(__FILE__) . "/../../data/$dataFileName", "r");

        if (!$handle) {
            $this->printToConsole("Could not load data file");
            exit;
        }

        $mappingHeader = fgetcsv($handle);
        unset($mappingHeader[0]);

        while (($rowData = fgetcsv($handle)) !== false) {
            $currentHubName = strtolower($rowData[0]);

            unset($rowData[0]);

            for ($i = 1; $i <= count($rowData); $i++) {
                $fromBranchName = strtolower($mappingHeader[$i]);
                $zoneCode = strtoupper($rowData[$i]);
                $this->printToConsole("Mapping $currentHubName to $fromBranchName with $zoneCode...");

                /** @var Branch $fromBranch */
                $fromBranch = Branch::findFirstByName($fromBranchName);
                if (!$fromBranch) {
                    $this->printToConsole("Could not map $currentHubName to $fromBranchName with $zoneCode. Reason: $fromBranchName not found");
                    continue;
                }

                /** @var Branch $currentHub */
                $currentHub = Branch::findFirstByName($currentHubName);
                if (!$currentHub) {
                    $this->printToConsole("Could not map $currentHubName to $fromBranchName with $zoneCode. Reason: $currentHubName not found");
                    continue;
                }

                /** @var Zone $zone */
                $zone = Zone::findFirstByCode($zoneCode);
                if (!$zone) {
                    $this->printToConsole("Could not map $currentHubName to $fromBranchName with $zoneCode. Reason: $zoneCode zone not found");
                    continue;
                }

                $existingMapping = ZoneMatrix::findFirst(['conditions' => '(from_branch_id = :from_branch_id: AND to_branch_id = :to_branch_id:) OR (from_branch_id = :to_branch_id: AND to_branch_id = :from_branch_id:)', 'bind' => ['from_branch_id' => $fromBranch->getId(), 'to_branch_id' => $currentHub->getId()]]);

                if ($existingMapping) {
                    if ($zone->getId() == $existingMapping->getZoneId()) {
                        $this->printToConsole("Mapping: $currentHubName to $fromBranchName with $zoneCode already exists");
                        continue;
                    } else {
                        $this->printToConsole("Could not map $currentHubName to $fromBranchName with $zoneCode. Reason: mapping not consistent both ways");
                        continue;
                    }
                }

                $zoneMatrix = new ZoneMatrix();
                $zoneMatrix->setZoneId($zone->getId());
                $zoneMatrix->setFromBranchId($fromBranch->getId());
                $zoneMatrix->setToBranchId($currentHub->getId());
                $zoneMatrix->setStatus(Status::ACTIVE);

                if (!$zoneMatrix->save()) {
                    $this->printToConsole("Could not map $currentHubName to $fromBranchName with $zoneCode. Reason: " . $this->printStringArray($zoneMatrix->getMessages()));
                    continue;
                }

                $this->printToConsole("Successfully mapped $currentHubName to $fromBranchName with $zoneCode");

            }
        }

    }

}