@php
    /** @var \RohitShakya\Beacon\Presenters\BeaconNotification $ui */

    $isUnread = $ui->notification->read_at === null;

    $iconClass = $ui->iconClass() ?: 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200';

    // icon can be:
    // 1. raw SVG string
    // 2. class name
    // 3. null → fallback
    $icon = $ui->icon();

    $url = $ui->url();

    $rowBase = 'group flex items-start gap-3 px-5 py-4 border-b transition-colors cursor-pointer';

    $rowBg = $isUnread
        ? 'bg-white dark:bg-neutral-800 border-neutral-200 dark:border-neutral-700'
        : 'bg-neutral-50 dark:bg-neutral-900 border-neutral-100 dark:border-neutral-800';

    $rowHover = 'hover:bg-neutral-100 dark:hover:bg-neutral-700/60';

    $titleClass = $isUnread
        ? 'font-bold text-neutral-900 dark:text-neutral-100'
        : 'font-medium text-neutral-700 dark:text-neutral-300';

    // detect if icon is raw svg
    $isSvg = is_string($icon) && str_contains($icon, '<svg');
@endphp

<button type="button" class="{{ $rowBase }} {{ $rowBg }} {{ $rowHover }} w-full text-left"
    wire:click="markRead('{{ $ui->notification->id }}')"
    @if ($url) x-data x-on:click="$nextTick(() => { window.location.href = @js($url) })" @endif>

    {{-- ICON --}}
    <div class="mt-0.5 h-10 w-10 shrink-0 rounded-full flex items-center justify-center {{ $iconClass }}">

        {{-- custom svg provided --}}
        @if ($isSvg)
            {!! $icon !!}

            {{-- class icon --}}
        @elseif ($icon)
            <i class="{{ $icon }} w-5 h-5"></i>

            {{-- default bell icon --}}
        @else
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="min-w-0 flex-1">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">

                <div class="text-base flex gap-2 items-center leading-snug truncate {{ $titleClass }}">

                    {{-- LINK ICON --}}
                    @if ($url)
                        <span class="opacity-80 group-hover:opacity-100 text-blue-700 dark:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                            </svg>
                        </span>
                    @endif

                    {{ $ui->title() }}
                </div>

                @if ($ui->body())
                    <div class="mt-0.5 text-sm text-neutral-600 dark:text-neutral-400 leading-snug line-clamp-2">
                        {!! $ui->body() !!}
                    </div>
                @endif

                <div class="mt-2 text-xs text-neutral-500">
                    {{ $ui->notifiedAt()->format('l g:i A') }}
                    <span class="mx-1">•</span>
                    {{ $ui->notifiedAt()->diffForHumans() }}
                </div>
            </div>

            {{-- UNREAD DOT --}}
            @if ($isUnread)
                <span class="mt-1.5 inline-flex h-2 w-2 rounded-full bg-blue-600 dark:bg-blue-400"></span>
            @endif
        </div>
    </div>
</button>
