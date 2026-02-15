<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Beacon View Overrides
    |--------------------------------------------------------------------------
    |
    | Here you may override the Blade views used by the Beacon UI. These views
    | control how notifications are rendered across the application.
    |
    | topbar → Notification bell dropdown shown in the navbar.
    | inbox  → Full inbox page showing all notifications.
    | item   → Single notification row component.
    |
    | You may publish the package views and point these to your own custom
    | implementations if you wish to fully control the UI.
    |
    */

    'views' => [
        'topbar' => 'beacon::topbar.default',
        'inbox'  => 'beacon::inbox.default',
        'item'   => 'beacon::item.default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Topbar Configuration
    |--------------------------------------------------------------------------
    |
    | Controls how the notification dropdown in the top navigation behaves.
    |
    | driver:
    |  Determines how the topbar is rendered.
    |   - livewire → interactive realtime dropdown (recommended)
    |   - blade    → static Blade rendering
    |
    | limit:
    |  Number of latest notifications shown inside the dropdown panel.
    |
    | view:
    |  Allows overriding only the topbar view independently from global views.
    |
    */

    'topbar' => [
        'driver' => 'livewire',
        'limit'  => 8,
        'view'   => 'beacon::topbar.default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Realtime Notification Settings
    |--------------------------------------------------------------------------
    |
    | Enables realtime notification listening via Laravel Echo (Reverb,
    | Pusher, Soketi, etc.).
    |
    | enabled:
    |  If true, the frontend will subscribe to a private broadcast channel
    |  for the authenticated user and update the UI instantly.
    |
    | channel:
    |  Broadcast channel pattern. `{id}` will be replaced with the current
    |  authenticated user's ID.
    |
    | browser_event:
    |  The browser event dispatched when a new notification is received.
    |  You can listen to this in Alpine or JavaScript:
    |
    |  window.addEventListener('beacon:notification', e => { ... });
    |
    */

    'realtime' => [
        'enabled'       => true,
        'channel'       => 'App.Models.User.{id}',
        'browser_event' => 'beacon:notification',
    ],

    /*
    |--------------------------------------------------------------------------
    | Beacon Routes
    |--------------------------------------------------------------------------
    |
    | Controls the internal routes used by the Beacon package. These routes
    | power actions such as fetching notifications, marking them as read,
    | and displaying the inbox page.
    |
    | enabled:
    |  Disable this if you want to fully handle routes manually.
    |
    | prefix:
    |  Base URI for all beacon routes. Example: /beacon/inbox
    |
    | middleware:
    |  Middleware stack applied to all routes. Typically includes `auth`.
    |
    */

    'routes' => [
        'enabled'    => true,
        'prefix'     => '/beacon',
        'middleware' => ['web', 'auth'],
    ],

];
