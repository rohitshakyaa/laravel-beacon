<?php

namespace RohitShakya\Beacon\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use RohitShakya\Beacon\Actions\NotificationActions;
use RohitShakya\Beacon\Presenters\BeaconNotification;

class Topbar extends Component
{
    public int $limit = 8;

    public bool $open = false;

    public function mount(?int $limit = null): void
    {
        $this->limit = $limit ?: (int) config('beacon.topbar.limit', 8);
        $this->refreshTopbar();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;

        if ($this->open) {
            $this->refreshTopbar();
        }
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function refreshTopbar(): void
    {
        unset($this->list);
        unset($this->unreadCount);
    }

    #[Computed]
    public function items()
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        $list = $user->notifications()
            ->latest()
            ->limit($this->limit)
            ->get();

        return $list->map(fn(DatabaseNotification $n) => BeaconNotification::make($n))->all();
    }

    #[Computed]
    public function unreadCount(): int
    {
        $user = auth()->user();
        return $user ? (int) $user->unreadNotifications()->count() : 0;
    }

    public function markAllRead(NotificationActions $actions): void
    {
        $user = auth()->user();
        if (! $user) return;

        $actions->markAllRead($user);
        $this->refreshTopbar();
    }

    public function markRead(string $id, NotificationActions $actions): void
    {
        $user = auth()->user();
        if (! $user) return;

        $actions->markRead($user, $id);
        $this->refreshTopbar();
    }

    public function markUnread(string $id, NotificationActions $actions): void
    {
        $user = auth()->user();
        if (! $user) return;

        $actions->markUnread($user, $id);
        $this->refreshTopbar();
    }

    public function deleteNotification(string $id, NotificationActions $actions): void
    {
        $user = auth()->user();
        if (! $user) return;

        $actions->delete($user, $id);
        $this->refreshTopbar();
    }

    /**
     * Fired from JS (Echo/Reverb) via window event -> Livewire.dispatch(...)
     */
    #[On('beacon-topbar-broadcast')]
    public function onBroadcast(array $payload = []): void
    {
        $this->refreshTopbar();
    }

    public function render()
    {
        return view('beacon::livewire.topbar');
    }
}
