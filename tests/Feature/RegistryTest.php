<?php

namespace RohitShakya\Beacon\Tests\Feature;

use InvalidArgumentException;
use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Registry\NotificationRegistry;

class RegistryTest extends TestCase
{
    public function test_can_register_and_get_definition(): void
    {
        /** @var NotificationRegistry $registry */
        $registry = $this->app->make('beacon.registry');

        $registry->register(DummyNotification::class, [
            'view' => 'notifications.dummy',
            'severity' => 'success',
        ]);

        $this->assertTrue($registry->has(DummyNotification::class));

        $def = $registry->get(DummyNotification::class);
        $this->assertIsArray($def);
        $this->assertSame('notifications.dummy', $def['view']);
        $this->assertSame('success', $def['severity']);
        $this->assertSame('DummyNotification', $def['label']); // default label
    }

    public function test_get_returns_null_when_unknown(): void
    {
        /** @var NotificationRegistry $registry */
        $registry = $this->app->make('beacon.registry');

        $this->assertNull($registry->get(\App\Notifications\Nope::class));
    }

    public function test_register_throws_for_invalid_class(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var NotificationRegistry $registry */
        $registry = $this->app->make('beacon.registry');

        $registry->register('NotAReal\\ClassName', []);
    }
}

// Dummy notification class just for tests
class DummyNotification {}
