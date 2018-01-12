<?php

namespace Surge\LaravelSalesforce\Objects;

class User extends AbstractObject
{
    /**
     * Check if user already exists on SF.
     * $params = [
     *  'Email' => 'test@test.com'
     * ]
     *
     * @param $params
     * @param string $condition
     * @return bool|mixed
     */
    public function exists($params, $condition = 'AND')
    {
        if (empty($params)) {
            return false;
        }

        $query = 'SELECT Id, Name, Email, UserType, UserRoleId FROM ' . $this->getType() . ' WHERE ';

        $paramsWithKeys = [];
        foreach ($params as $fieldName => $fieldValue) {
            $paramsWithKeys[] = $fieldName . ' = \'' . addslashes(trim($fieldValue)) . '\'';
        }

        $query .= '(' . implode(' ' . $condition . ' ', $paramsWithKeys) . ')';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }

}
