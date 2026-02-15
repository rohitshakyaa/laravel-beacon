@props([
    'title' => 'Nothing here',
    'subtitle' => null,
])

<div class="px-4 py-10 text-center">
    <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
        {{ $title }}
    </div>
    @if ($subtitle)
        <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
            {{ $subtitle }}
        </div>
    @endif
</div>
