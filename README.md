
# 🔔 Beacon

### Realtime Notification UI for Laravel

> Like a lighthouse in the dark, **Beacon** signals new activity to your users.

Beacon is a drop-in notification UI for Laravel that provides a **topbar dropdown**, **inbox page**, and **realtime updates** using Livewire and broadcasting.
It is designed to be elegant, customizable, and easy to integrate into any Laravel app.

---

## Why “Beacon”?

A **beacon** is a guiding signal — a lighthouse that alerts ships of activity and direction.

This package acts the same way:

* Signals new notifications
* Guides users to important updates
* Works in realtime
* Always visible from the topbar

Instead of silently storing notifications in the database, Beacon **announces them** to users through UI, events, and realtime broadcasting.

---

## Features

* Topbar notification dropdown
* Full inbox page
* Realtime updates (Echo / Reverb / Pusher)
* Livewire powered UI
* Fully customizable Blade views
* Browser events for JS integrations
* Auth-aware routing
* Plug-and-play config

---

## 📦 Installation

```bash
composer require your-vendor/beacon
```

Publish config:

```bash
php artisan vendor:publish --tag=beacon-config
```

Publish views (optional):

```bash
php artisan vendor:publish --tag=beacon-views
```

---

## Quick Usage

### 1. Add the topbar

```blade
<x-beacon::topbar />
```

Or if using Livewire driver:

```blade
@livewire('beacon.topbar')
```

---

### 2. Send a notification

```php
$user->notify(new SomeNotification());
```

Beacon will automatically appear in UI.

---

### 3. Listen in JS (optional)

```js
window.addEventListener('beacon:notification', (e) => {
    console.log('New notification', e.detail);
});
```

---

## Realtime Setup

Beacon supports:

* Laravel Reverb
* Pusher
* Soketi
* Ably
* Any Echo driver

Make sure Echo is running.

Example:

```js
window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        window.dispatchEvent(
            new CustomEvent('beacon:notification', { detail: notification })
        );
    });
```

---

## Configuration

`config/beacon.php`

```php
return [

    'views' => [
        'topbar' => 'beacon::topbar.default',
        'inbox'  => 'beacon::inbox.default',
        'item'   => 'beacon::item.default',
    ],

    'topbar' => [
        'driver' => 'livewire',
        'limit'  => 8,
        'view'   => 'beacon::topbar.default',
    ],

    'realtime' => [
        'enabled'       => true,
        'channel'       => 'App.Models.User.{id}',
        'browser_event' => 'beacon:notification',
    ],

    'routes' => [
        'enabled'    => true,
        'prefix'     => '/beacon',
        'middleware' => ['web', 'auth'],
    ],
];
```

---

## Config Options Explained

### Views

Override UI templates.

```php
'views' => [
    'topbar' => 'beacon::topbar.default',
    'inbox'  => 'beacon::inbox.default',
    'item'   => 'beacon::item.default',
],
```

You can replace with your own:

```php
'topbar' => 'resources/views/components/my-topbar.blade.php'
```

---

### Topbar

```php
'topbar' => [
    'driver' => 'livewire',
    'limit'  => 8,
]
```

| Option | Description                     |
| ------ | ------------------------------- |
| driver | `livewire` or `blade`           |
| limit  | notifications shown in dropdown |

---

### Realtime

```php
'realtime' => [
    'enabled' => true,
    'channel' => 'App.Models.User.{id}',
    'browser_event' => 'beacon:notification',
]
```

| Option        | Description             |
| ------------- | ----------------------- |
| enabled       | Enable Echo listening   |
| channel       | Private channel pattern |
| browser_event | Event fired in browser  |

---

### Routes

```php
'routes' => [
    'enabled' => true,
    'prefix' => '/beacon',
    'middleware' => ['web', 'auth'],
]
```

Provides:

* `/beacon/inbox`
* mark as read
* fetch notifications

---

## Customizing UI

Publish views:

```bash
php artisan vendor:publish --tag=beacon-views
```

Then edit:

```
resources/views/vendor/beacon/
```

You can redesign everything.

---

## Browser Events

Beacon dispatches:

```
beacon:notification
```

Example:

```js
window.addEventListener('beacon:notification', e => {
    toast(e.detail.title)
})
```

---

## Middleware

By default:

```php
'middleware' => ['web', 'auth']
```

You may change to:

```php
['web', 'auth:sanctum']
```

---

## Testing Notifications

Create a test page:

```php
$user->notify(new TestNotification());
```

Open two tabs → watch realtime.

---

## Use Cases

* SaaS dashboards
* Admin panels
* HR systems
* CRM
* ISP panels
* Any Laravel app needing notifications

---

## Contributing

PRs welcome.

```bash
git clone
composer install
npm install
```

---

## License

MIT

---

## Author

Built with ❤️ for Laravel ecosystem.
