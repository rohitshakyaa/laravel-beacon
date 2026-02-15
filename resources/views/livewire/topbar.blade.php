<div x-data="{
    open: false,
    toggle() { this.open = !this.open },
    close() { this.open = false },
}" x-on:keydown.escape.window="close()" class="relative">
    {{-- Trigger --}}
    <button type="button" x-on:click="toggle()" aria-haspopup="menu" aria-label="Open notifications" :aria-expanded="open"
        class="relative inline-flex items-center justify-center rounded-lg p-2
               text-neutral-700 hover:bg-neutral-100
               focus:outline-none focus:ring-2 focus:ring-neutral-200
               dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:ring-neutral-700/60">
        <span aria-hidden="true">🔔</span>

        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 inline-flex min-w-[18px] items-center justify-center rounded-full
                         bg-red-600 px-1.5 py-0.5 text-[10px] font-semibold leading-none text-white">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Panel --}}
    <div x-cloak x-show="open" x-transition.origin.top.right x-on:click.outside="close()"
        class="absolute right-0 z-50 mt-2 w-96 overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-lg
               dark:border-neutral-700/60 dark:bg-neutral-900"
        role="menu" aria-label="Notifications">
        {{-- Header --}}
        <div
            class="flex items-start justify-between gap-3 border-b border-neutral-200 px-4 py-3 dark:border-neutral-700/60">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Notifications</div>
                <div class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">{{ $this->unreadCount }} unread</div>
            </div>

            <x-beacon::button variant="outline" size="sm" type="button" wire:click="markAllRead">
                Mark all read
            </x-beacon::button>
        </div>

        {{-- Body --}}
        <div class="max-h-96 overflow-auto">
            @if (count($this->items) === 0)
                <x-beacon::empty-state title="No notifications" subtitle="You’re all caught up." />
            @else
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700/60">
                    @foreach ($this->items as $ui)
                        <div class="px-4 py-3">
                            @include($ui->view(), ['ui' => $ui])

                            <div class="mt-2 flex items-center gap-1.5">
                                @if ($ui->notification->read_at)
                                    <x-beacon::button variant="ghost" size="sm" type="button"
                                        wire:click="markUnread('{{ $ui->notification->id }}')">
                                        Unread
                                    </x-beacon::button>
                                @else
                                    <x-beacon::button variant="ghost" size="sm" type="button"
                                        wire:click="markRead('{{ $ui->notification->id }}')">
                                        Mark read
                                    </x-beacon::button>
                                @endif

                                <x-beacon::button variant="danger" size="sm" type="button"
                                    wire:click="deleteNotification('{{ $ui->notification->id }}')">
                                    Delete
                                </x-beacon::button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="border-t border-neutral-200 px-4 py-3 dark:border-neutral-700/60">
            <x-beacon::button as="a" href="{{ url('/notifications') }}" variant="ghost" size="md">
                View all
            </x-beacon::button>
        </div>
    </div>

    {{-- JS bridge hook (Echo -> Livewire) --}}
    @if (config('beacon.realtime.enabled', true))
        <script>
            (function() {
                if (window.__beaconTopbarBound) return;
                window.__beaconTopbarBound = true;

                const eventName = @json(config('beacon.realtime.browser_event', 'beacon:notification'));

                window.addEventListener(eventName, function(e) {
                    if (window.Livewire?.dispatch) {
                        window.Livewire.dispatch('beacon-topbar-broadcast', e.detail ?? {});
                    }
                });
            })();
        </script>
    @endif
</div>
