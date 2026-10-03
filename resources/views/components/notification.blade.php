@props([
    // Action drives both the icon and the accent colour: created, updated,
    // deleted, or a plain refusal that needs no action wording.
    'message',
    'action' => 'created',
])

@php
    $tones = [
        'created' => [
            'wrap' => 'border-green-200 bg-green-50 text-green-800',
            'icon' => 'check-circle',
            'accent' => 'bg-green-500',
        ],
        'updated' => [
            'wrap' => 'border-cyan-200 bg-cyan-50 text-cyan-800',
            'icon' => 'pencil',
            'accent' => 'bg-cyan-500',
        ],
        'deleted' => [
            'wrap' => 'border-red-200 bg-red-50 text-red-800',
            'icon' => 'trash',
            'accent' => 'bg-red-500',
        ],
        'error' => [
            'wrap' => 'border-red-200 bg-red-50 text-red-800',
            'icon' => 'info',
            'accent' => 'bg-red-500',
        ],
        // For the auth and profile flows, which confirm something without
        // having created, updated or deleted a record.
        'notice' => [
            'wrap' => 'border-brand-200 bg-brand-50 text-brand-800',
            'icon' => 'info',
            'accent' => 'bg-brand-500',
        ],
    ];

    $tone = $tones[$action] ?? $tones['created'];
@endphp

{{--
    One notification in the bottom-right stack.

    Slides in from the right, can be dismissed by hand or times out on its own,
    and fades out either way. The left accent bar carries the action colour so
    the three read apart even at a glance.
--}}
<div x-data="{ shown: true }"
    x-show="shown"
    x-transition:enter="transform ease-out duration-300"
    x-transition:enter-start="translate-x-6 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-6 opacity-0"
    x-init="setTimeout(() => shown = false, 6000)"
    role="status"
    aria-live="polite"
    class="pointer-events-auto relative flex w-full max-w-sm items-start gap-3 overflow-hidden rounded-lg border p-4 pl-5 shadow-lg {{ $tone['wrap'] }}">
    <span class="absolute inset-y-0 left-0 w-1 {{ $tone['accent'] }}" aria-hidden="true"></span>

    <x-icon :name="$tone['icon']" class="mt-0.5 h-5 w-5 shrink-0" />

    <p class="flex-1 text-sm font-medium">{{ $message }}</p>

    <button type="button"
        x-on:click="shown = false"
        aria-label="Dismiss notification"
        class="-m-1 shrink-0 rounded p-1 opacity-60 transition hover:opacity-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current">
        <x-icon name="x" class="h-4 w-4" />
    </button>
</div>