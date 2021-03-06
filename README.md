[![StyleCI](https://styleci.io/repos/96553078/shield?branch=master)](https://styleci.io/repos/96553078)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Surge-Financial/laravel-salesforce/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Surge-Financial/laravel-salesforce/?branch=master)
[![Build Status](https://travis-ci.org/Surge-Financial/laravel-salesforce.svg?branch=master)](https://travis-ci.org/Surge-Financial/laravel-salesforce)
# Salesforce package for Laravel

This package makes it easy to communicate with Salesforce.

## Installation

You can install the package via composer:

``` bash
composer require surge-financial/salesforce-laravel:"dev-master"
```

## .env Configs

Set the following variables in .env
``` bash
SALESFORCE_AUTH_ENDPOINT=<AUTH_ENDPOINT> (e.g. https://login.salesforce.com/services/oauth2/token for the salesforce live env)
SALESFORCE_CLIENT_ID=<CLIENT_ID>
SALESFORCE_CLIENT_SECRET=<SECRET>
SALESFORCE_USERNAME=<USERNAME>
SALESFORCE_PASSWORD=<PASSWORD>
SALESFORCE_URL=<URL> (e.g. https://eu6.salesforce.com)
SALESFORCE_LEAD_RT=<LEAD_RECORD_TYPE>
SALESFORCE_ACCOUNT_RT=<ACCOUNT_RECORD_TYPE>
SALESFORCE_OPPURTUNITY_RT=<OPPURTUNITY_RECORD_TYPE>
SALESFORCE_TASK_RT=<TASK_RECORD_TYPE>
SALESFORCE_BRAND=<BRAND>
SALESFORCE_BCC_EMAIL=<BBC_EMAIL>
```

## Service provider
Second you must install the service provider:

``` php
// config/app.php
'providers' => [
    ...
    Surge\LaravelSalesforce\SalesforceServiceProvider::class
],

'aliases' => [
    ...
    'Salesforce'   => Surge\LaravelSalesforce\Facade::class,   
]

```

## Publish package files
Next publish the config with:

``` bash
php artisan vendor:publish --provider="Surge\LaravelSalesforce\SalesforceServiceProvider"
```

## Register log event
In order to log all Salesforce requests and responses - add the following code in Events/EventsServiceProvider $listen array.
``` php

RequestSent::class => [
    <YourListenerClass>::class,
]

ResponseReceived::class => [
    <YourListenerClass>::class,
]

```

## Usage:

``` php

use Salesforce;


//Get opportunity
Salesforce::getOpportunity($id);

//Create new account
Salesforce::createAccount($params);

//Check if account exists
Salesforce::existsAccount(['PersonEmail' => 'test@test.com']);

//To check for more than one parameter with OR condition
Salesforce::existsAccount(['PersonEmail' => 'test@test.com', 'Phone' => '07846000111'], 'OR');

```

## Working on local
By default on local environment it is disabled.
To enable the package to work on local mode:
``` bash
SALESFORCE_DISABLE_ON_LOCAL=false
```
## Using the salesforce sandbox
You must first setup a sandbox environment on salesforce.
Then modify the following variables in your .env.
``` bash
SALESFORCE_AUTH_ENDPOINT (e.g. https://test.salesforce.com/services/oauth2/token)
SALESFORCE_USERNAME
SALESFORCE_PASSWORD
```
Also if your Laravel env is local then ensure that the following key is set to false:
``` bash
SALESFORCE_DISABLE_ON_LOCAL=false
```
## Upgrading to version 0.2.5
Ensure that you republish the config file so that the following line appears at the top:
``` bash
return [
    'auth_endpoint'   => env('SALESFORCE_AUTH_ENDPOINT'),
    ...
    ]
```