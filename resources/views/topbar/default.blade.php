@php
    /** @var \RohitShakya\Beacon\View\Components\Topbar $component */
@endphp

<div x-data="{ open: false }" class="relative">
    {{-- Trigger --}}
    <button type="button" @click="open = !open" class="relative inline-flex items-center">
        <span aria-hidden="true">🔔</span>

        @if ($unreadCount > 0)
            <span class="absolute -top-2 -right-2 text-xs px-1.5 py-0.5 rounded-full bg-red-600 text-white">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Panel --}}
    <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-80 rounded-lg border bg-white shadow">
        <div class="px-3 py-2 border-b font-semibold flex items-center justify-between">
            <span>Notifications</span>
            <span class="text-xs text-gray-500">{{ $unreadCount }} unread</span>
        </div>

        <div class="max-h-80 overflow-auto">
            @if (count($items) === 0)
                <div class="px-3 py-6 text-sm text-gray-500 text-center">
                    No notifications.
                </div>
            @else
                @foreach ($items as $ui)
                    <div class="px-3 py-2 border-b">
                        {{-- Each item uses the per-notification view resolution --}}
                        @include($ui->view(), ['ui' => $ui])
                    </div>
                @endforeach
            @endif
        </div>

        <div class="px-3 py-2 text-sm">
            <a href="{{ url('/notifications') }}">View all</a>
        </div>
    </div>
</div>
