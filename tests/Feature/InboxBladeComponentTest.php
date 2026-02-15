<?php

namespace RohitShakya\Beacon\Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Tests\TestUser;

class InboxBladeComponentTest extends TestCase
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

    public function test_inbox_blade_renders_notifications(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);
        $this->be($user);

        $this->createNotification($user, InboxType::class, ['title' => 'Hello Inbox']);
        $this->createNotification($user, InboxType::class, ['title' => 'Second']);

        $html = $this->blade('<x-beacon::inbox />')->__toString();

        $this->assertStringContainsString('Notifications', $html);
        $this->assertStringContainsString('Hello Inbox', $html);
        $this->assertStringContainsString('Second', $html);
    }
}

class InboxType {}
