<?php

namespace RohitShakya\Beacon\Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Tests\TestUser;

class TopbarComponentTest extends TestCase
{
    protected function createNotification(TestUser $user, string $type, array $data = [], ?string $readAt = null): void
    {
        DatabaseNotification::create([
            'id' => (string) Str::uuid(),
            'type' => $type,
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->id,
            'data' => $data,
            'read_at' => $readAt,
        ]);
    }

    public function test_topbar_renders_for_authenticated_user_and_shows_items_and_unread_count(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);

        $this->be($user);

        // 2 unread + 1 read
        $this->createNotification($user, DummyType::class, ['title' => 'N1', 'body' => 'B1']);
        $this->createNotification($user, DummyType::class, ['title' => 'N2']);
        $this->createNotification($user, DummyType::class, ['title' => 'N3'], now()->toDateTimeString());

        $html = $this->blade('<x-beacon::topbar />')->__toString();

        $this->assertStringContainsString('Notifications', $html);
        $this->assertStringContainsString('2 unread', $html);
        $this->assertStringContainsString('N1', $html);
        $this->assertStringContainsString('N2', $html);
        $this->assertStringContainsString('N3', $html);
    }

    public function test_topbar_view_can_be_overridden_by_config(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);
        $this->be($user);

        config()->set('beacon.views.topbar', 'beacon::topbar.alt');

        $html = $this->blade('<x-beacon::topbar />')->__toString();

        $this->assertStringContainsString('data-testid="beacon-topbar-alt"', $html);
        $this->assertStringContainsString('ALT TOPBAR', $html);
    }
}

class DummyType {}
