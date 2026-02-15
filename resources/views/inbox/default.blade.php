@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator<\RohitShakya\Beacon\Presenters\BeaconNotification>|null $notifications */
@endphp

<div class="beacon-inbox border rounded-lg bg-white">
    <div class="p-3 border-b flex items-center justify-between">
        <div class="font-semibold">Notifications</div>
        @if ($notifications)
            <div class="text-xs text-gray-500">
                Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}
            </div>
        @endif
    </div>

    {{-- Filters (Blade version is "static" unless user passes props) --}}
    <div class="p-3 border-b text-sm text-gray-600">
        <div class="flex flex-wrap gap-2 items-center">
            <span class="px-2 py-1 rounded bg-gray-100">All</span>
            <span class="px-2 py-1 rounded bg-gray-100">Unread</span>

            <span class="ml-2 text-xs text-gray-400">Type:</span>
            <select class="border rounded px-2 py-1 text-sm" disabled>
                <option>Use Livewire for filters</option>
                @foreach ($typeOptions as $opt)
                    <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="divide-y">
        @if (!$notifications)
            <div class="p-6 text-sm text-gray-500 text-center">Please login.</div>
        @elseif($notifications->count() === 0)
            <div class="p-6 text-sm text-gray-500 text-center">No notifications.</div>
        @else
            @foreach ($notifications as $ui)
                <div class="p-3">
                    @include($ui->view(), ['ui' => $ui])
                </div>
            @endforeach
        @endif
    </div>

    @if ($notifications)
        <div class="p-3 border-t">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
