@props([
    // Each entry is ['id' => int, 'label' => string, 'count' => int].
    'data',
    // Traits get one more row than breeds, so the wider ramp above is actually
    // reachable instead of wrapping after six.
    'max' => 7,
    'linkRoute' => null,
])

@php
    // The full orange ramp from tailwind.config.js, 200 through 900. Steps 50
    // and 100 are left out: they are so close to the cream page that a band in
    // those shades would read as a hole rather than a colour.
    $palette = [
        '#ffd9a3', // 200
        '#fdc078', // 300
        '#f9b04a', // 400
        '#f99d34', // 500
        '#e88c2a', // 600
        '#c96f14', // 700
        '#a8560c', // 800
        '#6b3407', // 900
    ];

    $total = (int) $data->sum('count');

    $ordered = $data->sortByDesc('count')->values();

    // Horizontal bands rather than tiles. A hen silhouette is concave at the
    // leg gap, the comb and the tail, and a squarified layout inside those
    // notches produces slivers whose visible area no longer matches the value.
    // Stacked bands stay honest: each one is a full-width stripe clipped to the
    // silhouette, so its height still reads as its share, and the gap between
    // the legs simply shows background.
    $bands = [];
    $cursor = 0.0;

    $items = $ordered->take($max);
    $restCount = $ordered->count() - $items->count();

    $rows = $items->map(fn ($entry, $i) => [
        'id' => $entry['id'],
        'label' => $entry['label'],
        'count' => (int) $entry['count'],
        'color' => $palette[$i % count($palette)],
    ])->all();

    if ($restCount > 0) {
        $rows[] = [
            'id' => null,
            'label' => 'Other',
            'count' => (int) $ordered->slice($max)->sum('count'),
            'color' => '#d6b9a6',
        ];
    }

    // The drawing area inside the 200x240 viewBox, in layout units. It spans the
    // comb at the top to the feet at the bottom so no band lands on bare outline.
    $top = 20.0;
    $bottom = 214.0;
    $height = $bottom - $top;

    foreach ($rows as $row) {
        $share = $total > 0 ? $row['count'] / $total : 0;
        $bandHeight = $share * $height;

        // A 2.5 unit hairline between bands keeps them readable at 56px.
        $bands[] = $row + [
            'y' => round($cursor, 2),
            'h' => round(max($bandHeight - 2.5, 1.5), 2),
            'pct' => $total > 0 ? round($share * 100, 1) : 0,
            'mid' => round($cursor + $bandHeight / 2, 2),
        ];

        $cursor += $bandHeight;
    }

    // The chicken is drawn in the SVG below from filled primitives, so the
    // bands run from near the top of the drawing area to the feet.
@endphp

<div class="chicken-chart flex flex-col items-center gap-4 p-4 sm:flex-row sm:items-start sm:gap-6">
    <div class="relative shrink-0 self-center sm:self-auto">
        <svg viewBox="0 0 200 240" class="block h-56 w-auto" role="img"
            aria-label="Trait breakdown, each band sized by how many chickens carry that trait">
            {{--
                The silhouette is a mask built from filled primitives rather than
                one hand-tuned path. Each piece is a shape whose position can be
                reasoned about directly, so head, comb, beak, tail and legs stay
                recognisable instead of collapsing into a blob.

                The pieces are painted white because an SVG mask is
                luminance-based: white reveals, black hides. Painting the
                chicken black masks out the chicken and leaves nothing visible.

                The mask is the union of those pieces; the bands are painted
                through it, so every band is clipped to the chicken.
            --}}
            <defs>
                <mask id="chicken-shape">
                    <g fill="#fff">
                        {{-- Hen, side on: comb, beak and wattle, then the neck
                             down to a plump body with a fanned tail, and two
                             three-toed feet. --}}
                        <path d="M 148 40
                            L 152 22 L 160 36 L 166 18 L 174 36 L 182 26 L 186 40
                            C 193 48 191 57 184 61
                            L 197 66 L 183 72
                            C 179 82 172 86 166 79
                            C 163 75 167 71 172 71
                            C 169 97 164 119 155 137
                            C 149 153 141 169 127 177
                            C 107 187 77 185 59 173
                            C 43 162 35 146 37 130
                            L 20 116 L 33 110 L 16 94 L 31 92 L 18 76 L 33 80 L 26 62 L 43 74
                            C 55 89 74 101 92 105
                            C 110 109 126 103 136 91
                            C 144 81 146 62 148 40 Z" />

                        <path
                            d="M 79 166 L 89 166 L 89 199 L 99 205 L 97 211 L 89 206 L 90 215 L 84 216 L 82 206 L 77 213 L 72 210 L 78 200 L 73 198 Z" />
                        <path
                            d="M 111 166 L 121 166 L 121 199 L 131 205 L 129 211 L 121 206 L 122 215 L 116 216 L 114 206 L 109 213 L 104 210 L 110 200 L 105 198 Z" />
                    </g>
                </mask>

                {{-- The same shape with a fat stroke, which dilates it into an
                     outline that follows the comb and toes too. --}}
                <mask id="chicken-outline">
                    <g fill="#fff" stroke="#fff" stroke-width="6" stroke-linejoin="round" stroke-linecap="round">
                        <path d="M 148 40
                            L 152 22 L 160 36 L 166 18 L 174 36 L 182 26 L 186 40
                            C 193 48 191 57 184 61
                            L 197 66 L 183 72
                            C 179 82 172 86 166 79
                            C 163 75 167 71 172 71
                            C 169 97 164 119 155 137
                            C 149 153 141 169 127 177
                            C 107 187 77 185 59 173
                            C 43 162 35 146 37 130
                            L 20 116 L 33 110 L 16 94 L 31 92 L 18 76 L 33 80 L 26 62 L 43 74
                            C 55 89 74 101 92 105
                            C 110 109 126 103 136 91
                            C 144 81 146 62 148 40 Z" />

                        <path
                            d="M 79 166 L 89 166 L 89 199 L 99 205 L 97 211 L 89 206 L 90 215 L 84 216 L 82 206 L 77 213 L 72 210 L 78 200 L 73 198 Z" />
                        <path
                            d="M 111 166 L 121 166 L 121 199 L 131 205 L 129 211 L 121 206 L 122 215 L 116 216 L 114 206 L 109 213 L 104 210 L 110 200 L 105 198 Z" />
                    </g>
                </mask>
            </defs>

            {{-- Outline, painted first so the bands sit inside it. --}}
            <rect x="0" y="0" width="200" height="240" mask="url(#chicken-outline)"
                class="fill-surface-border" />

            @if ($total === 0)
                <rect x="0" y="0" width="200" height="240" mask="url(#chicken-shape)"
                    class="fill-stone-100" />
            @else
                @foreach ($bands as $i => $band)
                    <rect x="0" y="{{ $band['y'] }}" width="200" height="{{ $band['h'] }}"
                        fill="{{ $band['color'] }}" mask="url(#chicken-shape)"
                        class="band b-{{ $i + 1 }}" />
                @endforeach
            @endif
        </svg>

        {{-- Each tooltip sits at its own band's middle, which varies with the
             distribution, so it cannot be positioned from a fixed grid. --}}
        @foreach ($bands as $i => $band)
            <div class="tip tip-{{ $i + 1 }} absolute z-10 -translate-x-1/2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-brand-900/75 px-2.5 py-1.5 text-left shadow-lg backdrop-blur-sm"
                style="left: 55%; top: {{ $band['mid'] / 240 * 100 }}%">
                <p class="text-[11px] font-semibold leading-tight text-white">{{ $band['label'] }}</p>
                <p class="text-[10px] leading-tight text-stone-300">
                    {{ $band['count'] }} &times; &middot; {{ $band['pct'] }}%
                </p>
            </div>
        @endforeach
    </div>

    <div class="w-full min-w-0 flex-1 self-center sm:self-auto">
        <div
            class="flex items-center gap-2.5 border-b border-surface-border pb-1.5 text-[10px] font-semibold uppercase tracking-wide text-stone-400">
            <span class="h-2.5 w-2.5 shrink-0"></span>
            <span class="min-w-0 flex-1">Trait</span>
            <span class="w-9 shrink-0 text-right">Times</span>
            <span class="w-12 shrink-0 text-right">Share</span>
        </div>

        <ul class="mt-1.5 space-y-1.5 text-sm">
            @foreach ($bands as $band)
                @php
                    $href = $linkRoute !== null && $band['id'] !== null
                        ? route($linkRoute, ['trait' => $band['id']])
                        : null;
                @endphp

                <li>
                    @if ($href)
                        <a href="{{ $href }}"
                            class="-mx-1.5 flex items-center gap-2.5 rounded-lg px-1.5 py-1 transition hover:bg-brand-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500">
                            @include('components.partials.legend-row', ['block' => $band])
                        </a>
                    @else
                        <div class="flex items-center gap-2.5">
                            @include('components.partials.legend-row', ['block' => $band])
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        {{-- The counts are trait assignments, so they add up past the flock. --}}
        <p class="mt-2 text-xs leading-snug text-stone-400">
            {{ $total }} trait tags across your chickens. A chicken can carry more than one.
        </p>
    </div>
</div>

<style>
    .chicken-chart .band {
        cursor: pointer;
        transition: filter .15s ease;
    }

    .chicken-chart .band:hover {
        filter: saturate(1.35) brightness(.95);
    }

    .chicken-chart .tip {
        opacity: 0;
        pointer-events: none;
        transition: opacity .12s ease;
    }
@foreach ($bands as $i => $band)
    .chicken-chart:has(.b-{{ $i + 1 }}:hover) .tip-{{ $i + 1 }} {
        opacity: 1;
    }
@endforeach
</style>