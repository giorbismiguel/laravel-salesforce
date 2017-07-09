# Salesforce package for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/webpush.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/webpush)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/webpush/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/webpush)
[![StyleCI](https://styleci.io/repos/65542206/shield)](https://styleci.io/repos/65542206)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/6ac8b6d5-c215-4ba5-9a47-d1b312ec196d.svg?style=flat-square)](https://insight.sensiolabs.com/projects/6ac8b6d5-c215-4ba5-9a47-d1b312ec196d)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/webpush.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/webpush)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/webpush/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/webpush/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/webpush.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/webpush)

This package makes it easy to communicate with Salesfoce.

## Installation

You can install the package via composer:

``` bash
composer require laravel-salesforce/helper: "dev-master"
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

Add the following code in Events/EventsServiceProvider $listen array
``` php
SalesforceLog::class => [
    StoreSalesforceLog::class,
]
```


