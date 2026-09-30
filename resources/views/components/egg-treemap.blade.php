@props([
    'data',
    'max' => 6,
    'unit' => 'chicken',
])

@php
    /**
     * Squarified treemap: packs rectangles into the container so each area is
     * proportional to its value while keeping aspect ratios near square.
     * Based on the algorithm by Bruls, Huizing & van Wijk.
     */
    $worst = function (array $row, float $side): float {
        $values = array_column($row, 'value');
        $sum = array_sum($values);

        if ($sum <= 0) {
            return INF;
        }

        $sideSquared = $side * $side;
        $sumSquared = $sum * $sum;

        return max(
            $sideSquared * max($values) / $sumSquared,
            $sumSquared / ($sideSquared * min($values))
        );
    };

    $layout = function (array $row, array $rect): array {
        [$x, $y, $w, $h] = $rect;
        $total = array_sum(array_column($row, 'value'));
        $out = [];

        // Lay the row along whichever side of the remaining space is SHORTER,
        // since a row spread along the longer side produces thin slivers.
        if ($w <= $h) {
            $rowHeight = $total / $w;
            $cursor = $x;

            foreach ($row as $item) {
                $width = $item['value'] / $rowHeight;
                $out[] = ['item' => $item, 'x' => $cursor, 'y' => $y, 'w' => $width, 'h' => $rowHeight];
                $cursor += $width;
            }

            $rect = [$x, $y + $rowHeight, $w, $h - $rowHeight];
        } else {
            $rowWidth = $total / $h;
            $cursor = $y;

            foreach ($row as $item) {
                $height = $item['value'] / $rowWidth;
                $out[] = ['item' => $item, 'x' => $x, 'y' => $cursor, 'w' => $rowWidth, 'h' => $height];
                $cursor += $height;
            }

            $rect = [$x + $rowWidth, $y, $w - $rowWidth, $h];
        }

        return [$out, $rect];
    };

    /**
     * Egg silhouette, asymmetric on purpose: a narrow rounded top, the widest
     * point below centre, and a fuller rounded bottom. A symmetric curve just
     * reads as an oval.
     */
    $eggPath = 'M 100 8
        C 128 8 150 30 162 62
        C 176 96 184 124 184 148
        C 184 192 148 232 100 232
        C 52 232 16 192 16 148
        C 16 124 24 96 38 62
        C 50 30 72 8 100 8 Z';

    // Shades drawn from the app's own brand ramp, so the chart stays in the
    // same palette as the header and buttons.
    $palette = [
        '#f99d34',
        '#f9b04a',
        '#fdc078',
        '#ffd9a3',
        '#e88c2a',
        '#c96f14',
        '#a8560c',
    ];

    $total = (int) $data->sum();

    // sortDesc() keeps the breed names as keys, so read the labels and the
    // counts out in the same order. Calling ->values() on the collection would
    // drop the names and leave only the numeric keys behind.
    $ordered = $data->sortDesc();
    $labels = $ordered->keys()->values()->all();
    $counts = $ordered->values()->all();

    $items = [];
    foreach (array_slice($labels, 0, $max) as $i => $label) {
        $items[] = ['label' => $label, 'count' => (int) $counts[$i]];
    }

    if (count($counts) > $max) {
        $items[] = [
            'label' => 'Other',
            'count' => (int) array_sum(array_slice($counts, $max)),
        ];
    }

    // The layout box spans the egg's full bounding area, not an inset of it.
    // An inset left an unfilled margin at the top and bottom; letting the box
    // run to the edges means the clip trims the blocks into the silhouette and
    // the whole egg is coloured, with each gap filled by its nearest block.
    $box = [16.0, 8.0, 168.0, 224.0];
    $boxArea = $box[2] * $box[3];

    // Areas must sum to the container area, so scale each count into the share
    // of the box it is entitled to. The original count is kept for display.
    $scale = $total > 0 ? $boxArea / $total : 0.0;
    $items = array_map(fn (array $i) => $i + ['value' => $i['count'] * $scale], $items);

    $rects = [];
    $remaining = $box;
    $row = [];

    // Hairline separation between blocks, in layout units.
    $gap = 1.2;

    foreach ($items as $item) {
        $side = min($remaining[2], $remaining[3]);

        if ($side <= 0) {
            break;
        }

        if ($row === []) {
            $row = [$item];

            continue;
        }

        if ($worst([...$row, $item], $side) <= $worst($row, $side)) {
            $row[] = $item;

            continue;
        }

        [$placed, $remaining] = $layout($row, $remaining);
        $rects = [...$rects, ...$placed];
        $row = [$item];
    }

    if ($row !== []) {
        [$placed, $remaining] = $layout($row, $remaining);
        $rects = [...$rects, ...$placed];
    }

    $blocks = [];
    foreach ($rects as $i => $r) {
        $isOther = $r['item']['label'] === 'Other';

        // Inset each block slightly so a hairline gap separates neighbours.
        // Against the 3px egg stroke this also hides the outer margin, since
        // the stroke covers the first ~1.5 units inside the silhouette.
        $x = round($r['x'] + $gap, 2);
        $y = round($r['y'] + $gap, 2);
        $w = round(max($r['w'] - 2 * $gap, 1), 2);
        $h = round(max($r['h'] - 2 * $gap, 1), 2);

        $blocks[] = [
            'label' => $r['item']['label'],
            'count' => $r['item']['count'],
            'pct' => $total > 0 ? round($r['item']['count'] / $total * 100, 1) : 0,
            // Light neutral for the tail bucket, so it never collides with a
            // real breed when the palette wraps around.
            'color' => $isOther ? '#d6d3d1' : $palette[$i % count($palette)],
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
            'cx' => round($x + $w / 2, 2),
            'cy' => round($y + $h / 2, 2),
        ];
    }

    $plural = fn (int $n) => $n === 1 ? $unit : $unit.'s';
@endphp

<div class="egg-treemap flex flex-col items-center gap-4 p-4 sm:flex-row sm:items-start sm:gap-6">
    <div class="relative shrink-0 self-center sm:self-auto">
        <svg viewBox="0 0 200 240" class="block h-56 w-auto" role="img"
            aria-label="Breed breakdown, each block sized by chicken count">
            <defs>
                <clipPath id="egg-clip">
                    <path d="{{ $eggPath }}" />
                </clipPath>
            </defs>

            @if ($total === 0)
                <path d="{{ $eggPath }}" class="fill-stone-100" />
            @else
                @foreach ($blocks as $i => $block)
                    {{-- clip-path lives on the rect and is released on hover
                         (:hover { clip-path: none }), so a hovered block can
                         grow past the egg. Because the clip is gone by the
                         time the transform applies, the egg outline is never
                         dragged out of shape. --}}
                    <rect x="{{ $block['x'] }}" y="{{ $block['y'] }}" width="{{ $block['w'] }}"
                        height="{{ $block['h'] }}" fill="{{ $block['color'] }}"
                        clip-path="url(#egg-clip)" rx="1.5" ry="1.5"
                        class="slice s-{{ $i + 1 }}" />
                @endforeach
            @endif

            <path d="{{ $eggPath }}" fill="none" class="stroke-surface-border" style="stroke-width: 3" />
        </svg>

        {{-- One popup per block, revealed with :has() when its block is hovered. --}}
        @foreach ($blocks as $i => $block)
            <div class="tip tip-{{ $i + 1 }} absolute z-10 -translate-x-1/2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-brand-900/75 px-2.5 py-1.5 text-left shadow-lg backdrop-blur-sm"
                style="left: {{ $block['cx'] / 200 * 100 }}%; top: {{ $block['cy'] / 240 * 100 }}%">
                <p class="text-[11px] font-semibold leading-tight text-white">{{ $block['label'] }}</p>
                <p class="text-[10px] leading-tight text-stone-300">
                    {{ $block['count'] }} {{ $plural($block['count']) }} &middot; {{ $block['pct'] }}%
                </p>
            </div>
        @endforeach
    </div>

    <div class="w-full min-w-0 flex-1 self-center sm:self-auto">
        <div
            class="flex items-center gap-2.5 border-b border-surface-border pb-1.5 text-[10px] font-semibold uppercase tracking-wide text-stone-400">
            <span class="h-2.5 w-2.5 shrink-0"></span>
            <span class="min-w-0 flex-1">Breed</span>
            <span class="w-9 shrink-0 text-right">Birds</span>
            <span class="w-12 shrink-0 text-right">Share</span>
        </div>

        <ul class="mt-1.5 space-y-1.5 text-sm">
            @foreach ($blocks as $block)
                <li class="flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 shrink-0 rounded-full"
                        style="background: {{ $block['color'] }}"></span>
                    <span class="min-w-0 flex-1 truncate font-medium text-stone-700">{{ $block['label'] }}</span>
                    <span class="w-9 shrink-0 text-right tabular-nums text-stone-500">{{ $block['count'] }}</span>
                    <span class="w-12 shrink-0 text-right tabular-nums text-stone-400">{{ $block['pct'] }}%</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
    .egg-treemap .slice {
        cursor: pointer;
        transition: filter .15s ease;
    }

    /* Only the block under the cursor reacts, and only in colour: the shape
       never moves, so it stays clipped inside the egg at all times. */
    .egg-treemap .slice:hover {
        filter: saturate(1.35) brightness(.95);
    }

    .egg-treemap .tip {
        opacity: 0;
        pointer-events: none;
        transition: opacity .12s ease;
    }
@foreach ($blocks as $i => $block)
    .egg-treemap:has(.s-{{ $i + 1 }}:hover) .tip-{{ $i + 1 }} {
        opacity: 1;
    }
@endforeach
</style>
