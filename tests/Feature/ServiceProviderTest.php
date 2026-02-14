<?php

namespace RohitShakya\Beacon\Tests\Feature;

use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Registry\NotificationRegistry;

class ServiceProviderTest extends TestCase
{
    public function test_registry_is_singleton_bound(): void
    {
        $a = $this->app->make('beacon.registry');
        $b = $this->app->make('beacon.registry');

        $this->assertInstanceOf(NotificationRegistry::class, $a);
        $this->assertSame($a, $b); // same instance => singleton
    }

    public function test_config_is_merged(): void
    {
        $this->assertSame('beacon::item.default', config('beacon.views.item'));
        $this->assertSame(8, config('beacon.topbar.limit'));
    }
}
