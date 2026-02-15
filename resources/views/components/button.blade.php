@props([
    'as' => 'button',
    'variant' => 'outline',
    'size' => 'sm',
])

@php
    $sizes =
        [
            'sm' => 'h-9 px-3 text-xs',
            'md' => 'h-10 px-4 text-sm',
        ][$size] ?? 'h-9 px-3 text-xs';

    $variants =
        [
            'outline' => 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50
                      dark:border-neutral-700/60 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800',
            'soft' => 'border border-transparent bg-neutral-100 text-neutral-900 hover:bg-neutral-200
                      dark:bg-neutral-800 dark:text-neutral-100 dark:hover:bg-neutral-700/70',
            'ghost' => 'border border-transparent bg-transparent text-neutral-700 hover:bg-neutral-100
                      dark:text-neutral-200 dark:hover:bg-neutral-800',
            'danger' => 'border border-transparent bg-red-50 text-red-700 hover:bg-red-100
                      dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/15',
        ][$variant] ?? 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50';

    $base = "inline-flex items-center justify-center rounded-lg font-medium transition focus:outline-none focus:ring-2
             focus:ring-neutral-200 dark:focus:ring-neutral-700/60";
@endphp

@if ($as === 'a')
    <a {{ $attributes->merge(['class' => "$base $sizes $variants"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "$base $sizes $variants"]) }}>
        {{ $slot }}
    </button>
@endif
