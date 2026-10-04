@props([
    // The heading text. Kept as a slot so callers can include their own markup
    // where a page needs more than a plain string.
    'icon' => null,
    // Defaults to the margin a page heading wants; pass '' inside a header row
    // that already provides its own spacing.
    'headingClass' => 'mb-6',
])

<h1 class="flex items-center gap-3 text-3xl font-bold text-stone-900 {{ $headingClass }}">
    @if ($icon)
        <x-icon :name="$icon" class="h-8 w-8 shrink-0 text-brand-600" />
    @endif

    {{ $slot }}
</h1>