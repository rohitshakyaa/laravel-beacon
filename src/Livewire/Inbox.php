<?php

namespace RohitShakya\Beacon\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use RohitShakya\Beacon\Support\NotificationFeed;

class Inbox extends Component
{
    use WithPagination;

    public int $perPage = 15;
    public bool $unreadOnly = false;
    public ?string $type = null;

    protected $queryString = [
        'unreadOnly' => ['as' => 'unread', 'except' => false],
        'type' => ['except' => null],
        'page' => ['except' => 1],
    ];

    public function updatedUnreadOnly(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function markRead(string $id): void
    {
        app(\RohitShakya\Beacon\Actions\NotificationActions::class)->markRead(auth()->user(), $id);
        $this->resetPage();
    }

    public function markUnread(string $id): void
    {
        app(\RohitShakya\Beacon\Actions\NotificationActions::class)->markUnread(auth()->user(), $id);
        $this->resetPage();
    }

    public function deleteNotification(string $id): void
    {
        app(\RohitShakya\Beacon\Actions\NotificationActions::class)->delete(auth()->user(), $id);
        $this->resetPage();
    }

    public function markAllRead(): void
    {
        app(\RohitShakya\Beacon\Actions\NotificationActions::class)->markAllRead(auth()->user());
    }


    public function render(NotificationFeed $feed)
    {
        $user = auth()->user();

        $notifications = $user
            ? $feed->inbox($user, $this->perPage, $this->unreadOnly, $this->type)
            : null;

        $typeOptions = $feed->typeOptions();

        return view('beacon::livewire.inbox', compact('notifications', 'typeOptions'));
    }
}
