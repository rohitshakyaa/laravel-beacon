@php
    /** @var \RohitShakya\Beacon\Presenters\BeaconNotification $ui */
@endphp

<div class="flex items-start gap-3">
    <div class="mt-0.5 h-9 w-9 shrink-0 rounded-lg bg-neutral-100 flex items-center justify-center text-neutral-700">
        {{ $ui->icon() ? '★' : '•' }}
    </div>

    <div class="min-w-0 flex-1">
        <div class="text-sm font-semibold text-neutral-900">
            {{ $ui->title() }}
        </div>

        @if ($ui->body())
            <div class="mt-0.5 text-xs text-neutral-600">
                {{ $ui->body() }}
            </div>
        @endif

        @if ($ui->url())
            <a href="{{ $ui->url() }}" class="mt-2 inline-flex text-xs font-medium text-neutral-900 hover:underline">
                Open
            </a>
        @endif
    </div>

    @if ($ui->notification->read_at === null)
        <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
    @endif
</div>
