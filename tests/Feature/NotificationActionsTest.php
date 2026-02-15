<?php

namespace RohitShakya\Beacon\Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use RohitShakya\Beacon\Actions\NotificationActions;
use RohitShakya\Beacon\Tests\TestCase;
use RohitShakya\Beacon\Tests\TestUser;

class NotificationActionsTest extends TestCase
{
    protected function createNotification(TestUser $user, ?string $readAt = null): DatabaseNotification
    {
        return DatabaseNotification::create([
            'id' => (string) Str::uuid(),
            'type' => DummyType::class,
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->id,
            'data' => ['title' => 'X'],
            'read_at' => $readAt,
        ]);
    }

    public function test_mark_read_and_unread(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);
        $actions = app(NotificationActions::class);

        $n = $this->createNotification($user, null);
        $this->assertNull($n->fresh()->read_at);

        $actions->markRead($user, $n->id);
        $this->assertNotNull($n->fresh()->read_at);

        $actions->markUnread($user, $n->id);
        $this->assertNull($n->fresh()->read_at);
    }

    public function test_mark_all_read(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);
        $actions = app(NotificationActions::class);

        $this->createNotification($user, null);
        $this->createNotification($user, null);

        $count = $actions->markAllRead($user);
        $this->assertSame(2, $count);

        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    public function test_delete_notification(): void
    {
        $user = TestUser::create(['name' => 'Sarkar']);
        $actions = app(NotificationActions::class);

        $n = $this->createNotification($user, null);

        $actions->delete($user, $n->id);

        $this->assertDatabaseMissing('notifications', ['id' => $n->id]);
    }

    public function test_user_cannot_mutate_others_notification(): void
    {
        $a = TestUser::create(['name' => 'A']);
        $b = TestUser::create(['name' => 'B']);

        $n = $this->createNotification($a, null);

        $actions = app(NotificationActions::class);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $actions->markRead($b, $n->id);
    }
}

class DummyType {}
