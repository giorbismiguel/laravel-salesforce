<?php

namespace SalesforceHelper;

use SalesforceHelper\Events\SalesforceLog;

class StoreSalesforceLog
{
    /**
     * Handle the event.
     *
     * @param SalesforceLog $event
     * @return void
     */
    public function handle(SalesforceLog $event)
    {
        //todo log Salesforce activity
//        activity()
//            ->withProperties([
//                'ip_address'   => request()->ip(),
//                'request_body' => $event->log['options'],
//            ])
//            ->log('Salesforce - ' . $event->log['class'] . ' - ' . $event->log['type'] . ' URL:' . $event->log['url'] . '');
    }
}
