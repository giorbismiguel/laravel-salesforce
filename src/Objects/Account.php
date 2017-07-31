<?php

namespace Surge\LaravelSalesforce\Objects;

class Account extends AbstractObject
{
    /**
     * Insert new account.
     *
     * @param $params
     *
     * @return bool
     */
    public function insert($params)
    {
        $params['RecordTypeId'] = config('laravel-salesforce.record_type.account');

        return $this->createRecord($this->getType(), $params);
    }

    /**
     * Check if account already exists on SF.
     *
     * @param string $email
     *
     * @return bool|array
     */
    public function checkAlreadyExists($email)
    {
        $query = 'SELECT Id, OwnerId  FROM '.$this->getType().' WHERE PersonEmail = \''.addslashes(trim($email)).'\' AND RecordTypeId = \''.config('laravel-salesforce.record_type.account').'\'';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
