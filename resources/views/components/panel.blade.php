@props([
    'title' => null,
    'subtitle' => null,
    'footer' => null,
    'padded' => true,
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-xl border border-neutral-200 bg-white shadow-sm
                    dark:border-neutral-700/60 dark:bg-neutral-900',
    ]) }}>
    @if ($title || $subtitle || isset($header))
        <div
            class="flex items-start justify-between gap-3 border-b border-neutral-200 px-4 py-3
                    dark:border-neutral-700/60">
            <div class="min-w-0">
                @if ($title)
                    <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ $title }}
                    </div>
                @endif
                @if ($subtitle)
                    <div class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                        {{ $subtitle }}
                    </div>
                @endif
            </div>

            @isset($header)
                <div class="shrink-0">
                    {{ $header }}
                </div>
            @endisset
        </div>
    @endif

    <div class="{{ $padded ? 'px-4 py-3' : '' }}">
        {{ $slot }}
    </div>

    @if ($footer || isset($footerSlot))
        <div class="border-t border-neutral-200 px-4 py-3 dark:border-neutral-700/60">
            {{ $footer ?? ($footerSlot ?? '') }}
        </div>
    @endif
</div>
