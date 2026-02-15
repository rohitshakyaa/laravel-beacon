<?php

namespace RohitShakya\Beacon;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use RohitShakya\Beacon\Registry\NotificationRegistry;
use RohitShakya\Beacon\View\Components\Topbar;

class BeaconServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/beacon.php', 'beacon');

        $this->app->singleton(\RohitShakya\Beacon\Actions\NotificationActions::class, fn() => new \RohitShakya\Beacon\Actions\NotificationActions());


        $this->app->singleton('beacon.registry', function () {
            return new NotificationRegistry();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/beacon.php' => config_path('beacon.php'),
        ], 'beacon-config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'beacon');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/beacon'),
        ], 'beacon-views');

        // $this->loadViewComponentsAs('beacon', [
        //     Topbar::class,
        // ]);

        Blade::componentNamespace('RohitShakya\\Beacon\\View\\Components', 'beacon');

        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('beacon.inbox', \RohitShakya\Beacon\Livewire\Inbox::class);
        }

        $routes = config('beacon.routes', []);
        if (($routes['enabled'] ?? true) === true) {
            $prefix = $routes['prefix'] ?? '/beacon';
            $middleware = $routes['middleware'] ?? ['web', 'auth'];

            \Illuminate\Support\Facades\Route::group([
                'prefix' => trim($prefix, '/'),
                'middleware' => $middleware,
            ], function () {
                require __DIR__ . '/../routes/beacon-actions.php';
            });
        }

        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('beacon.topbar', \RohitShakya\Beacon\Livewire\Topbar::class);
            \Livewire\Livewire::component('beacon.inbox', \RohitShakya\Beacon\Livewire\Inbox::class);
        }
    }
}
