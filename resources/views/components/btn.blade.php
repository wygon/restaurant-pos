@props(['color' => 'blue'])

@php
    $colorClasses = match($color) {
        'red' => 'bg-red-500 hover:bg-red-600',
        'green' => 'bg-green-500 hover:bg-green-600',
        'gray' => 'bg-gray-500 hover:bg-gray-600',
        'orange' => 'bg-orange-500 hover:bg-orange-600',
        'purple' => 'bg-purple-600 hover:bg-purple-700',
        default => 'bg-blue-600 hover:bg-blue-700'
    };
@endphp

@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => "text-white px-4 py-2.5 rounded transition shadow-sm $colorClasses"]) }}>
        {{ $slot }}
    </a>
@else
<button {{ $attributes->merge(['class' => "text-white px-4 py-2 rounded transition shadow-sm $colorClasses"]) }}>
    {{ $slot }}
</button>
@endif