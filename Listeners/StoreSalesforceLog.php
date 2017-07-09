<?php

namespace App\Listeners;

class StoreSalesforceLog
{
    /**
     * Handle the event.
     *
     * @param SalesforceLog $event
     * @return void
     */
    public function handle($event)
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
