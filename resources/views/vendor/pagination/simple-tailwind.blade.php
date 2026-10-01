{{--
    Branded pagination, previous/next only. Matches tailwind.blade.php so a
    simple-paginated list does not drop back to Laravel's gray defaults.
--}}
@if ($paginator->hasPages())
    @php
        $button = 'inline-flex items-center px-3.5 py-2 text-sm font-semibold leading-5 transition';
        $idle = 'border border-surface-border bg-surface text-brand-800 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500';
        $disabled = 'cursor-not-allowed border border-surface-border bg-stone-50 text-stone-400';
    @endphp

    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
        class="flex items-center justify-between gap-2">

        @if ($paginator->onFirstPage())
            <span class="{{ $button }} {{ $disabled }}" aria-disabled="true">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="{{ $button }} {{ $idle }}">{!! __('pagination.previous') !!}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="{{ $button }} {{ $idle }}">{!! __('pagination.next') !!}</a>
        @else
            <span class="{{ $button }} {{ $disabled }}" aria-disabled="true">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
