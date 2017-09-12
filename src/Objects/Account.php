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
     * $params = [
     *  'PersonEmail' => 'test@test.com'
     * ]
     *
     *
     * @param array $param to search
     * @return bool|array
     */
    public function exists($params)
    {
        if(empty($params)) {
            return false;
        }

        $query = 'SELECT Id, OwnerId  FROM ' . $this->getType() . ' WHERE RecordTypeId = \'' . config('laravel-salesforce.record_type.account') . '\'';

        foreach($params as $fieldName => $fieldValue) {
            $query .= ' AND ' . $fieldName . '=\'' . addslashes(trim($fieldValue)) . '\'';
        }

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
