<?php

namespace Surge\LaravelSalesforce\Objects;

class Account extends AbstractObject
{
    /**
     * Insert new account.
     *
     * @param $params
     */
    public function create(array $params)
    {
        $params['RecordTypeId'] = config('laravel-salesforce.record_type.account');

        return parent::create($params);
    }

    /**
     * Check if account already exists on SF.
     *
     * @param string $email
     *
     * @return bool|array
     */
    public function exists($phone = null, $email = null)
    {
        $query = 'SELECT Id, OwnerId  FROM ' . $this->getType() . ' WHERE PersonEmail = \'' . addslashes(trim($email)) . '\' AND RecordTypeId = \'' . config('laravel-salesforce.record_type.account') . '\'';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
