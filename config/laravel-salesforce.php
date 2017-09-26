<?php

return [

    'client_id'     => env('SALESFORCE_CLIENT_ID'),
    'client_secret' => env('SALESFORCE_CLIENT_SECRET'),
    'username'      => env('SALESFORCE_USERNAME'),
    'password'      => env('SALESFORCE_PASSWORD'),
    'domain'        => env('SALESFORCE_URL'),

    'brand' => env('SALESFORCE_BRAND'),

    'record_type' => [
        'lead'        => env('SALESFORCE_RECORD_TYPE_LEAD'),
        'account'     => env('SALESFORCE_RECORD_TYPE_ACCOUNT'),
        'opportunity' => env('SALESFORCE_RECORD_TYPE_OPPORTUNITY')
    ],

    'disable_on_local' => env('SALESFORCE_DISABLE_ON_LOCAL', true)
];
