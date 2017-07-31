<?php

return [

    'client_id'     => env('SALESFORCE_CLIENT_ID'),
    'client_secret' => env('SALESFORCE_CLIENT_SECRET'),
    'username'      => env('SALESFORCE_USERNAME'),
    'password'      => env('SALESFORCE_PASSWORD'),
    'domain'        => env('SALESFORCE_URL'),

    'leadrecordtypeid'        => env('SALESFORCE_LEAD_RT'),
    'accountrecordtypeid'     => env('SALESFORCE_ACCOUNT_RT'),
    'oppurtunityrecordtypeid' => env('SALESFORCE_OPPURTUNITY_RT'),
    'brand'                   => env('SALESFORCE_BRAND'),
];
