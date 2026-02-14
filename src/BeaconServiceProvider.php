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
    }
}
