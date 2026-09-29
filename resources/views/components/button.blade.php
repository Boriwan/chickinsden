@props([
    'href' => null,
    'type' => 'button',
    'color' => 'orange',
])

@php
    $palette = [
        'orange' => 'bg-brand text-gray-900 hover:bg-brand-dark focus-visible:outline-brand',
        'red' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
        'gray' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus-visible:outline-gray-400',
        'white' => 'border border-gray-300 bg-white text-gray-800 hover:bg-gray-50 focus-visible:outline-gray-400',
    ];

    $base = 'inline-flex cursor-pointer items-center gap-2 rounded-md px-5 py-2.5 font-medium transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2';

    $classes = trim($base . ' ' . ($palette[$color] ?? $palette['orange']));

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
