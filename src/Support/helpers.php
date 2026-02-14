<?php

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\App;
use RohitShakya\Beacon\Presenters\BeaconNotification;
use RohitShakya\Beacon\Registry\NotificationRegistry;

if (! function_exists('beacon_registry')) {
    function beacon_registry(): NotificationRegistry
    {
        /** @var NotificationRegistry $registry */
        $registry = App::make('beacon.registry');
        return $registry;
    }
}

if (! function_exists('beacon')) {
    function beacon(DatabaseNotification $notification): BeaconNotification
    {
        return BeaconNotification::make($notification);
    }
}
