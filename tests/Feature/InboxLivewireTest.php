<?php

namespace RohitShakya\Beacon\Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Livewire\Livewire;
use RohitShakya\Beacon\Livewire\Inbox;
use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Tests\TestUser;

class InboxLivewireTest extends TestCase
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

    public function test_livewire_inbox_filters_unread(): void
    {
        if (! class_exists(\Livewire\Livewire::class)) {
            $this->markTestSkipped('Livewire not installed.');
        }

        $user = TestUser::create(['name' => 'Sarkar']);
        $this->be($user);

        $this->createNotification($user, LWType::class, ['title' => 'Unread One']);
        $this->createNotification($user, LWType::class, ['title' => 'Read One'], now()->toDateTimeString());

        Livewire::test(Inbox::class)
            ->assertSee('Unread One')
            ->assertSee('Read One')
            ->set('unreadOnly', true)
            ->assertSee('Unread One')
            ->assertDontSee('Read One');
    }
}

class LWType {}
