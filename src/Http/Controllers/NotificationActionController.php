<?php

namespace RohitShakya\Beacon\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RohitShakya\Beacon\Actions\NotificationActions;

class NotificationActionController
{
    public function __construct(protected NotificationActions $actions) {}

    public function read(Request $request, string $id): RedirectResponse
    {
        $this->actions->markRead($request->user(), $id);
        return back();
    }

    public function unread(Request $request, string $id): RedirectResponse
    {
        $this->actions->markUnread($request->user(), $id);
        return back();
    }

    public function readAll(Request $request): RedirectResponse
    {
        $this->actions->markAllRead($request->user());
        return back();
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $this->actions->delete($request->user(), $id);
        return back();
    }
}
