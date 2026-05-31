@props(['color' => 'blue'])

@php
    $colorClasses = match($color) {
        'red' => 'border-red-300 text-red-600 hover:bg-red-50',
        'green' => 'border-green-300 text-green-600 hover:bg-green-50',
        'gray' => 'border-gray-400 text-gray-600 hover:bg-gray-50',
        'orange' => 'border-orange-300 text-orange-600 hover:bg-orange-50',
        'yellow' => 'border-yellow-300 text-yellow-600 hover:bg-yellow-50',
        default => 'border-blue-300 text-blue-600 hover:bg-blue-50',
    };
@endphp

@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => "inline-block text-center text-xs border px-3 py-1 rounded transition cursor-pointer $colorClasses"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "text-xs border px-3 py-1 rounded transition $colorClasses"]) }}>
        {{ $slot }}
    </button>
@endif