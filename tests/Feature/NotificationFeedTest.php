<?php

namespace RohitShakya\Beacon\Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use RohitShakya\Beacon\Support\NotificationFeed;
use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Tests\TestUser;

class NotificationFeedTest extends TestCase
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

    public function test_inbox_filter_unread_only_and_type(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);

        $this->createNotification($user, TypeA::class, ['title' => 'A1']); // unread
        $this->createNotification($user, TypeA::class, ['title' => 'A2'], now()->toDateTimeString()); // read
        $this->createNotification($user, TypeB::class, ['title' => 'B1']); // unread

        $feed = app(NotificationFeed::class);

        $all = $feed->inbox($user, perPage: 50);
        $this->assertSame(3, $all->total());

        $unread = $feed->inbox($user, perPage: 50, unreadOnly: true);
        $this->assertSame(2, $unread->total());

        $typeAUnread = $feed->inbox($user, perPage: 50, unreadOnly: true, type: TypeA::class);
        $this->assertSame(1, $typeAUnread->total());
        $this->assertSame('A1', $typeAUnread->items()[0]->title());
    }
}

class TypeA {}
class TypeB {}
