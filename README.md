[![StyleCI](https://styleci.io/repos/96553078/shield?branch=master)](https://styleci.io/repos/96553078)

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
SALESFORCE_CLIENT_ID=<CLIENT_ID>
SALESFORCE_CLIENT_SECRET=<SECRET>
SALESFORCE_USERNAME=<USERNAME>
SALESFORCE_PASSWORD=<PASSWORD>
SALESFORCE_URL=https://eu6.salesforce.com
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

//Get opportunity
\Salesforce::getOpportunity($id);

```
