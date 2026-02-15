<?php

namespace RohitShakya\Beacon\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use RohitShakya\Beacon\Support\NotificationFeed;

class Inbox extends Component
{
    public int $perPage;
    public bool $unreadOnly;
    public ?string $type;

    protected NotificationFeed $feed;

    public function __construct(
        NotificationFeed $feed,
        int $perPage = 15,
        bool $unreadOnly = false,
        ?string $type = null
    ) {
        $this->feed = $feed;
        $this->perPage = $perPage;
        $this->unreadOnly = $unreadOnly;
        $this->type = $type;
    }

    public function render(): View
    {
        $user = auth()->user();

        $notifications = $user
            ? $this->feed->inbox($user, $this->perPage, $this->unreadOnly, $this->type)
            : null;

        $typeOptions = $this->feed->typeOptions();

        return view(config('beacon.views.inbox', 'beacon::inbox.default'), compact('notifications', 'typeOptions'));
    }
}
