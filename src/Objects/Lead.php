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
     * @param string $params
     *
     * @return bool|array
     */
    public function exists($params)
    {
        //return false if not enough data provided
        if (empty($params)) {
            return false;
        }

        $query = 'SELECT Id, OwnerId  FROM ' . $this->getType() . ' AND RecordTypeId = \'' . config('laravel-salesforce.record_type.lead') . '\'';

        foreach ($params as $fieldName => $fieldValue) {
            $query .= ' AND ' . $fieldName . '=\'' . addslashes(trim($fieldValue)) . '\'';
        }

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
