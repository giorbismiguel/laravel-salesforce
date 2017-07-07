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
composer require laravel-notification-channels/webpush
```





First you must install the service provider:

``` php
// config/app.php
'providers' => [
    ...
    NotificationChannels\WebPush\WebPushServiceProvider::class,
],
```

Add the `NotificationChannels\WebPush\HasPushSubscriptions` trait to your `User` model:

``` php
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Model
{
    use HasPushSubscriptions;
}
```

``` php
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return WebPushMessage::create()
            // ->id($notification->id)
            ->title('Approved!')
            ->icon('/approved-icon.png')
            ->body('Your account was approved!')
            ->action('View account', 'view_account');
    }
}
```
