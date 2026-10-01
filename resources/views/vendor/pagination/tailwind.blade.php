{{--
    Branded pagination for the app palette.

    Laravel's default view ships gray buttons plus a `dark:` set that switches on
    the OS preference. This app has no dark theme, so on a machine set to dark
    the controls rendered near-black against a cream page. The `dark:` variants
    are gone for that reason — the brand tokens below are the only palette.

    The strip is one bordered wrapper divided by `divide-x`, rather than each
    button drawing its own border. Per-button borders left 1px gaps in the top
    and bottom edges wherever a side was removed to fake a divider; a wrapper
    border plus `divide-x` gives a continuous outline and unbroken dividers.
--}}
@if ($paginator->hasPages())
    @php
        // Every cell is the same width so dividers land evenly and the numbers
        // line up with the arrows.
        $cell = 'inline-flex h-10 w-11 shrink-0 items-center justify-center text-sm font-semibold transition';
        $idle = 'bg-surface text-brand-800 hover:bg-brand-50 hover:text-brand-900 focus-visible:outline focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand-500';
        $current = 'bg-brand-500 text-stone-900';
        $disabled = 'cursor-not-allowed bg-stone-50 text-stone-400';
        $arrow = fn (string $path) => '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="'.$path.'" clip-rule="evenodd" /></svg>';
        $prevPath = 'M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z';
        $nextPath = 'M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z';
    @endphp

    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
        class="flex items-center justify-between gap-4">

        {{-- Compact controls on small screens --}}
        <div class="flex flex-1 items-center justify-between gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-lg border border-surface-border px-3.5 py-2 text-sm font-semibold {{ $disabled }}"
                    aria-disabled="true">{!! __('pagination.previous') !!}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="inline-flex items-center rounded-lg border border-surface-border bg-surface px-3.5 py-2 text-sm font-semibold text-brand-800 transition hover:bg-brand-50 hover:text-brand-900">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="inline-flex items-center rounded-lg border border-surface-border bg-surface px-3.5 py-2 text-sm font-semibold text-brand-800 transition hover:bg-brand-50 hover:text-brand-900">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center rounded-lg border border-surface-border px-3.5 py-2 text-sm font-semibold {{ $disabled }}"
                    aria-disabled="true">{!! __('pagination.next') !!}</span>
            @endif
        </div>

        <div class="hidden flex-1 items-center justify-between gap-4 sm:flex">
            <p class="text-sm text-stone-600">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-semibold text-stone-900">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-stone-900">{{ $paginator->lastItem() }}</span>
                @else
                    <span class="font-semibold text-stone-900">{{ $paginator->count() }}</span>
                @endif
                {!! __('of') !!}
                <span class="font-semibold text-stone-900">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>

            {{-- One wrapper supplies the outline; `divide-x` supplies the dividers --}}
            <div class="inline-flex items-stretch divide-x divide-surface-border overflow-hidden rounded-lg border border-surface-border bg-surface shadow-sm">
                {{-- Previous page --}}
                @if ($paginator->onFirstPage())
                    <span class="{{ $cell }} {{ $disabled }}" aria-disabled="true"
                        aria-label="{{ __('pagination.previous') }}">{!! $arrow($prevPath) !!}</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="{{ $cell }} {{ $idle }}"
                        aria-label="{{ __('pagination.previous') }}">{!! $arrow($prevPath) !!}</a>
                @endif

                {{-- Page numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="{{ $cell }} {{ $disabled }} font-normal">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $cell }} {{ $current }}" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $cell }} {{ $idle }}"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    aria-current="false">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next page --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="{{ $cell }} {{ $idle }}"
                        aria-label="{{ __('pagination.next') }}">{!! $arrow($nextPath) !!}</a>
                @else
                    <span class="{{ $cell }} {{ $disabled }}" aria-disabled="true"
                        aria-label="{{ __('pagination.next') }}">{!! $arrow($nextPath) !!}</span>
                @endif
            </div>
        </div>
    </nav>
@endif
