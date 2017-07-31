<?php

namespace Surge\LaravelSalesforce\Objects;

class Lead extends AbstractObject
{
    /**
     * Insert new lead.
     *
     * @param $params
     */
    public function create(array$params)
    {
        $params['RecordTypeId'] = config('laravel-salesforce.record_type.lead');

        return parent::create($params);
    }

    /**
     * Check if lead already exists on SF.
     *
     * @param string $phone
     * @param string $email
     *
     * @return bool|array
     */
    public function checkAlreadyExists($phone = null, $email = null)
    {
        //return false if not enough data provided
        if ($email === null && $phone === null) {
            return false;
        }

        if ($email !== null) {
            $query = 'SELECT Id, OwnerId  FROM '.$this->getType().' WHERE Email = \''.addslashes(trim($email)).'\'';
        } else {
            $query = 'SELECT Id, OwnerId  FROM '.$this->getType().' WHERE Phone = \''.addslashes(trim($phone)).'\'';
        }

        $query .= ' AND RecordTypeId = \''.config('laravel-salesforce.record_type.lead').'\'';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return array_shift($response->records);
        }

        return false;
    }
}
