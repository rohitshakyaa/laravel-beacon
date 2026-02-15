@props([
    'items' => [], // [['label'=>'All','active'=>true,'attrs'=>[]], ...]
])

<div
    class="inline-flex rounded-lg border border-neutral-200 bg-neutral-50 p-1
            dark:border-neutral-700/60 dark:bg-neutral-800">
    @foreach ($items as $item)
        @php
            $active = (bool) ($item['active'] ?? false);
            $attrs = $item['attrs'] ?? [];
        @endphp

        <button type="button" {{ $attributes->except('items')->merge($attrs) }}
            class="rounded-md px-3 py-1.5 text-xs font-medium transition
                   {{ $active
                       ? 'bg-white text-neutral-900 shadow-sm dark:bg-neutral-900 dark:text-neutral-100'
                       : 'text-neutral-600 hover:bg-white dark:text-neutral-300 dark:hover:bg-neutral-900/50' }}">
            {{ $item['label'] ?? 'Item' }}
        </button>
    @endforeach
</div>
