<?php

namespace SalesforceHelper;

class Lead extends EnterpriseClient
{
    protected $objName = 'Lead';

    /**
     * Insert new lead
     *
     * @param $params
     * @return bool
     */
    public function insert($params)
    {
        $params['RecordTypeId'] = $this->leadRecord;

        return $this->createRecord($this->objName, $params);
    }

    /**
     * Check if lead already exists on SF
     *
     * @param string $phone
     * @param string $email
     * @param bool   $checkForAcc
     * @return bool|array
     */
    public function checkAlreadyExists($phone = null, $email = null, $checkForAcc = true)
    {
        //return false if not enough data provided
        if (!$email && !$phone) {
            return false;
        }

        //first check if there is account:
        if ($checkForAcc && $email) {
            $accountObj = new Account();
            if ($account = $accountObj->checkAlreadyExists($email, false)) {
                return $account;
            }
        }

        if ($email) {
            $query = 'SELECT Id, OwnerId  FROM ' . $this->objName . ' WHERE Email = \'' . addslashes(trim($email)) . '\'';
        } else {
            $query = 'SELECT Id, OwnerId  FROM ' . $this->objName . ' WHERE Phone = \'' . addslashes(trim($phone)) . '\'';
        }

        $query .= ' AND RecordTypeId = \'' . $this->leadRecord . '\'';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
