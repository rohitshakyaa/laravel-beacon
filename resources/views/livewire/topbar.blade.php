<div x-data="{
    open: false,
    toggle() { this.open = !this.open },
    close() { this.open = false },
}" x-on:keydown.escape.window="close()" class="relative">

    {{-- Trigger --}}
    <button type="button" x-on:click="toggle()" aria-haspopup="menu" aria-label="Open notifications" :aria-expanded="open"
        class="relative rounded-full h-9 w-9 flex items-center justify-center cursor-pointer
               bg-neutral-100 hover:bg-neutral-200
               dark:bg-neutral-900/50 dark:hover:bg-neutral-800/70
               ring-1 ring-transparent hover:ring-neutral-200
               dark:hover:ring-neutral-700/60
               transition">

        {{-- Bell icon (default) --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6 text-neutral-600 dark:text-neutral-300">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>

        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 inline-flex min-w-[18px] items-center justify-center rounded-full
                       bg-red-600 dark:bg-red-500
                       px-1.5 py-0.5 text-[10px] font-semibold leading-none text-white">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Panel --}}
    <div x-cloak x-show="open" x-transition.origin.top.right x-on:click.outside="close()"
        class="absolute right-0 z-50 mt-2 w-[28rem] overflow-hidden rounded-2xl border shadow-lg
               border-neutral-200 bg-white
               dark:border-neutral-700/60 dark:bg-neutral-900"
        role="menu" aria-label="Notifications">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 pt-5">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Notifications
            </h3>

            <div class="text-neutral-700 dark:text-neutral-200">
                <x-beacon::button variant="ghost" size="sm" type="button" wire:click="markAllRead">
                    Mark all as read
                </x-beacon::button>
            </div>
        </div>

        {{-- Tabs (optional if you have $unreadOnly) --}}
        <div class="px-5 py-4">
            <div class="inline-flex rounded-lg p-1 bg-neutral-100 dark:bg-neutral-800/70">
                <button type="button" wire:click="$set('unreadOnly', false)" @class([
                    'rounded-md px-4 py-1.5 text-sm font-medium cursor-pointer transition',
                    'bg-white shadow-sm text-neutral-900 ring-1 ring-neutral-200' => !$unreadOnly,
                    'text-neutral-600 hover:text-neutral-900' => $unreadOnly,
                    'dark:bg-neutral-900 dark:text-neutral-100 dark:ring-neutral-700/60' => !$unreadOnly,
                    'dark:text-neutral-300 dark:hover:text-neutral-100' => $unreadOnly,
                ])>
                    All
                </button>

                <button type="button" wire:click="$set('unreadOnly', true)" @class([
                    'rounded-md px-4 py-1.5 text-sm font-medium cursor-pointer transition',
                    'bg-white shadow-sm text-neutral-900 ring-1 ring-neutral-200' => $unreadOnly,
                    'text-neutral-600 hover:text-neutral-900' => !$unreadOnly,
                    'dark:bg-neutral-900 dark:text-neutral-100 dark:ring-neutral-700/60' => $unreadOnly,
                    'dark:text-neutral-300 dark:hover:text-neutral-100' => !$unreadOnly,
                ])>
                    Unread
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="max-h-96 overflow-auto border-t border-b border-neutral-200 dark:border-neutral-700/60">
            @if (count($this->items) === 0)
                <x-beacon::empty-state title="No notifications" subtitle="You’re all caught up." />
            @else
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700/60">
                    @foreach ($this->items as $ui)
                        {{-- each item view should now be tallstack-free too --}}
                        @include($ui->view(), ['ui' => $ui])
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="flex justify-center px-5 py-4">
            <a href="{{ url('/notifications') }}"
                class="inline-flex items-center gap-2 text-sm font-medium transition
                       text-neutral-700 hover:text-neutral-900
                       dark:text-neutral-300 dark:hover:text-neutral-100">
                View all notifications
                <span aria-hidden="true">→</span>
            </a>
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
