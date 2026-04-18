@props([
    'type'        => 'success',
    'dismissible' => true,
    'icon'        => null,
])

@php
    $styles = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error'   => 'bg-red-50   border-red-200   text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info'    => 'bg-blue-50  border-blue-200  text-blue-800',
    ];
    $icons = [
        'success' => '✅',
        'error'   => '❌',
        'warning' => '⚠️',
        'info'    => 'ℹ️',
    ];
    $style    = $styles[$type] ?? $styles['info'];
    $iconChar = $icon ?? ($icons[$type] ?? 'ℹ️');
@endphp

<div x-data="{ show: true }" x-show="show"
     class="border rounded-xl px-4 py-3 flex items-start space-x-3 text-sm animate-fade-in {{ $style }}"
     {{ $attributes }}>
    <span class="flex-shrink-0 mt-0.5">{{ $iconChar }}</span>
    <div class="flex-1">{{ $slot }}</div>
    @if($dismissible)
    <button @click="show = false"
            class="flex-shrink-0 opacity-60 hover:opacity-100 transition font-bold ml-2">✕</button>
    @endif
</div>
