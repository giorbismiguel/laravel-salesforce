<?php

namespace Surge\LaravelSalesforce\Objects;

class Lead extends AbstractObject
{
    /**
     * Insert new lead.
     *
     * @param $params
     */
    public function create(array $params)
    {
        $params['RecordTypeId'] = config('laravel-salesforce.record_type.lead');

        return parent::create($params);
    }

    /**
     * Check if lead already exists on SF.
     *
     * $params = [
     *   'Email' => 'test@test.com'
     * ]
     *
     * @param array  $params
     * @param string $condition
     *
     * @return bool|array
     */
    public function exists($params, $condition = 'AND')
    {
        //return false if not enough data provided
        if (empty($params)) {
            return false;
        }

        $query = 'SELECT Id, OwnerId FROM ' . $this->getType() . ' WHERE RecordTypeId = \'' . config('laravel-salesforce.record_type.lead') . '\'';

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
