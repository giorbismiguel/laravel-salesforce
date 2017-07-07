<?php

namespace SalesforceHelper;

class Account extends EnterpriseClient
{
    protected $objName = 'Account';

    /**
     * Insert new account
     *
     * @param $params
     * @return bool
     */
    public function insert($params)
    {
        $params['RecordTypeId'] = $this->accountRecord;

        return $this->createRecord($this->objName, $params);
    }

    /**
     * Check if account already exists on SF
     *
     * @param  string $email
     * @param  bool   $checkForLead
     * @return bool|array
     */
    public function checkAlreadyExists($email, $checkForLead = true)
    {
        $query = 'SELECT Id, OwnerId  FROM ' . $this->objName . ' WHERE PersonEmail = \'' . addslashes(trim($email)) . '\' AND RecordTypeId = \'' . $this->leadRecord . '\'';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        //also check if exists in Lead section
        if ($checkForLead) {
            $leadObj = new Lead();
            return $leadObj->checkAlreadyExists(null, $email, false);
        }

        return false;
    }
}
