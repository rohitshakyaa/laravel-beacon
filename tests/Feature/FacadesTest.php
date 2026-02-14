<?php

namespace RohitShakya\Beacon\Tests\Feature;

use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Facades\Beacon;

class FacadesTest extends TestCase
{
    public function test_facade_registers_into_registry(): void
    {
        Beacon::register(DummyFacadeNotification::class, [
            'view' => 'notifications.facade',
        ]);

        $def = Beacon::get(DummyFacadeNotification::class);

        $this->assertIsArray($def);
        $this->assertSame('notifications.facade', $def['view']);
    }
}

class DummyFacadeNotification {}
