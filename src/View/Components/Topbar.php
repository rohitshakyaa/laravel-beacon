<?php

namespace RohitShakya\Beacon\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\Component;
use RohitShakya\Beacon\Presenters\BeaconNotification;

class Topbar extends Component
{
    public int $limit;

    /** @var array<int, BeaconNotification> */
    public array $items = [];

    public int $unreadCount = 0;

    public function __construct(int $limit = 0)
    {
        $this->limit = $limit > 0 ? $limit : (int) config('beacon.topbar.limit', 8);

        $user = auth()->user();
        if (! $user) {
            return;
        }

        // unread count
        $this->unreadCount = (int) $user->unreadNotifications()->count();

        // latest notifications
        /** @var \Illuminate\Support\Collection<int, DatabaseNotification> $list */
        $list = $user->notifications()
            ->latest()
            ->limit($this->limit)
            ->get();

        $this->items = $list->map(fn(DatabaseNotification $n) => BeaconNotification::make($n))->all();
    }

    public function render(): View
    {
        return view(config('beacon.views.topbar', 'beacon::topbar.default'));
    }
}
