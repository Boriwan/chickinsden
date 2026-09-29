@props([
    'href' => null,
    'type' => 'button',
    'color' => 'orange',
])

@php
    $palette = [
        'orange' => 'bg-brand-500 text-stone-900 hover:bg-brand-600 focus-visible:outline-brand-500',
        'red' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
        'gray' => 'border border-stone-300 bg-white text-stone-700 hover:bg-stone-50 focus-visible:outline-stone-400',
        'ghost' => 'text-stone-600 hover:bg-stone-100 hover:text-stone-900 focus-visible:outline-stone-400',
    ];

    $base = 'inline-flex cursor-pointer items-center gap-2 rounded-lg px-4 py-2.5 font-semibold transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2';

    $classes = trim($base . ' ' . ($palette[$color] ?? $palette['orange']));

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
