<?php

namespace RohitShakya\Beacon\Actions;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;

class NotificationActions
{
    public function markRead(User $user, string $id): DatabaseNotification
    {
        $n = $this->findForUser($user, $id);

        if ($n->read_at === null) {
            $n->forceFill(['read_at' => Carbon::now()])->save();
        }

        return $n;
    }

    public function markUnread(User $user, string $id): DatabaseNotification
    {
        $n = $this->findForUser($user, $id);

        if ($n->read_at !== null) {
            $n->forceFill(['read_at' => null])->save();
        }

        return $n;
    }

    public function markAllRead(User $user): int
    {
        return $user->unreadNotifications()
            ->update(['read_at' => Carbon::now()]);
    }

    public function delete(User $user, string $id): void
    {
        $n = $this->findForUser($user, $id);
        $n->delete();
    }

    protected function findForUser(User $user, string $id): DatabaseNotification
    {
        /** @var DatabaseNotification|null $n */
        $n = $user->notifications()->whereKey($id)->first();

        abort_unless((bool)$n, 404);

        return $n;
    }
}
