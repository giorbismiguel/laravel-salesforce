<?php

class SalesforceAuth
{
    private $auth;

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }
}
