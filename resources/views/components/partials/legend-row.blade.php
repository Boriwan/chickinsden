{{-- One legend row: swatch, label, count and share. Shared by both charts so the two legends stay identical. --}}
<span class="flex w-full items-center gap-2.5">
    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $block['color'] }}"></span>
    <span class="min-w-0 flex-1 truncate font-medium text-stone-700">{{ $block['label'] }}</span>
    <span class="w-9 shrink-0 text-right tabular-nums text-stone-500">{{ $block['count'] }}</span>
    <span class="w-12 shrink-0 text-right tabular-nums text-stone-400">{{ $block['pct'] }}%</span>
</span>