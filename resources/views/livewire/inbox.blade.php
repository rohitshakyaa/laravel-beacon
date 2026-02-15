<x-beacon::panel title="Notifications" :subtitle="$notifications ? $notifications->total() . ' total' : null" class="beacon-inbox" :padded="false">
    <x-slot:header>
        <x-beacon::button variant="outline" size="sm" type="button" wire:click="markAllRead">
            Mark all read
        </x-beacon::button>
    </x-slot:header>

    {{-- Filters --}}
    <div class="border-b border-neutral-200 px-4 py-3 dark:border-neutral-700/60">
        <div class="flex flex-wrap items-center gap-2">
            <x-beacon::segmented :items="[
                [
                    'label' => 'All',
                    'active' => !$unreadOnly,
                    'attrs' => ['wire:click' => '$set(\'unreadOnly\', false)'],
                ],
                [
                    'label' => 'Unread',
                    'active' => (bool) $unreadOnly,
                    'attrs' => ['wire:click' => '$set(\'unreadOnly\', true)'],
                ],
            ]" />

            <div class="ml-auto flex items-center gap-2">
                <span class="text-xs text-neutral-500 dark:text-neutral-400">Type</span>

                <select wire:model.live="type"
                    class="h-9 rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-800
                           focus:outline-none focus:ring-2 focus:ring-neutral-200
                           dark:border-neutral-700/60 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:ring-neutral-700/60">
                    <option value="">All types</option>
                    @foreach ($typeOptions as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="divide-y divide-neutral-200 dark:divide-neutral-700/60">
        @if (!$notifications)
            <x-beacon::empty-state title="Please login"
                subtitle="You need an authenticated user to view notifications." />
        @elseif($notifications->count() === 0)
            <x-beacon::empty-state title="No notifications" subtitle="You’re all caught up." />
        @else
            @foreach ($notifications as $ui)
                <div class="px-4 py-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            @include($ui->view(), ['ui' => $ui])
                        </div>

                        <div class="flex shrink-0 items-center gap-1.5">
                            @if ($ui->notification->read_at)
                                <x-beacon::button variant="ghost" size="sm" type="button"
                                    wire:click="markUnread('{{ $ui->notification->id }}')">
                                    Unread
                                </x-beacon::button>
                            @else
                                <x-beacon::button variant="ghost" size="sm" type="button"
                                    wire:click="markRead('{{ $ui->notification->id }}')">
                                    Read
                                </x-beacon::button>
                            @endif

                            <x-beacon::button variant="danger" size="sm" type="button"
                                wire:click="deleteNotification('{{ $ui->notification->id }}')">
                                Delete
                            </x-beacon::button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if ($notifications)
        <x-slot:footerSlot>
            {{ $notifications->links() }}
        </x-slot:footerSlot>
    @endif
</x-beacon::panel>
