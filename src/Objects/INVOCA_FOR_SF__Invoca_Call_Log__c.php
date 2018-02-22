<?php
/**
 * Created by PhpStorm.
 * User: Giorbis Miguel
 * Date: 22/2/2018
 * Time: 6:04 PM
 */

namespace Surge\LaravelSalesforce\Objects;


class INVOCA_FOR_SF__Invoca_Call_Log__c extends AbstractObject
{
    /**
     * Check if INVOCA_FOR_SF__Invoca_Call_Log__c already exists on SF.
     * $params = [
     *  'INVOCA_FOR_SF__transaction_id__c' => 'transation_id'
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

        $query = 'SELECT Id, Paid__c FROM ' . $this->getType() . ' WHERE ';

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