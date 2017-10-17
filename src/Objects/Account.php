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
     * @param array  $param to search
     * @param string $condition
     * @return bool|array
     */
    public function exists($params, $condition = 'AND')
    {
        if (empty($params)) {
            return false;
        }

        $query = 'SELECT Id, OwnerId FROM ' . $this->getType() . ' WHERE RecordTypeId = \'' . config('laravel-salesforce.record_type.account') . '\'';

        $paramsWithKeys = [];
        foreach ($params as $fieldName => $fieldValue) {
            $paramsWithKeys[] = $fieldName . ' = \'' . addslashes(trim($fieldValue)) . '\'';
        }

        $query .= ' AND (' . implode(' ' . $condition . ' ', $paramsWithKeys) . ')';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
