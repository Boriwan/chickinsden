@props([
    'stats',
    'recent',
    'breedBreakdown',
])

{{-- Quick actions --}}
<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <a href="{{ route('chickens.create') }}"
        class="flex items-center gap-3 rounded-xl border border-surface-border bg-surface p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
        </span>
        <span>
            <span class="block font-semibold text-stone-900">Add a chicken</span>
            <span class="block text-sm text-stone-500">Log a new member of your den</span>
        </span>
    </a>

    <a href="{{ route('chickens.index') }}"
        class="flex items-center gap-3 rounded-xl border border-surface-border bg-surface p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
            <x-icon name="egg" class="h-5 w-5" />
        </span>
        <span>
            <span class="block font-semibold text-stone-900">My chickens</span>
            <span class="block text-sm text-stone-500">Browse your whole den</span>
        </span>
    </a>

    <a href="{{ route('breeds.index') }}"
        class="flex items-center gap-3 rounded-xl border border-surface-border bg-surface p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
            <x-icon name="book" class="h-5 w-5" />
        </span>
        <span>
            <span class="block font-semibold text-stone-900">Breeds Wiki</span>
            <span class="block text-sm text-stone-500">Look up breed details</span>
        </span>
    </a>

    @if (auth()->user()->is_admin)
        <a href="{{ route('admin.index') }}"
            class="flex items-center gap-3 rounded-xl border border-surface-border bg-surface p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
                <x-icon name="settings" class="h-5 w-5" />
            </span>
            <span>
                <span class="block font-semibold text-stone-900">Admin area</span>
                <span class="block text-sm text-stone-500">Manage every chicken and breed</span>
            </span>
        </a>
    @endif
</div>

{{-- Stats --}}
<div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @foreach ([
        ['label' => 'Chickens', 'value' => $stats['total'], 'icon' => 'egg'],
        ['label' => 'Males', 'value' => $stats['males'], 'icon' => 'feather'],
        ['label' => 'Females', 'value' => $stats['females'], 'icon' => 'feather'],
        ['label' => 'Breeds', 'value' => $stats['breeds'], 'icon' => 'book'],
    ] as $card)
        <div class="rounded-xl border border-surface-border bg-surface p-4 shadow-sm">
            <div class="mb-2 flex items-center gap-2 text-stone-500">
                <x-icon :name="$card['icon']" class="h-4 w-4" />
                <span class="text-sm font-medium">{{ $card['label'] }}</span>
            </div>
            <p class="text-3xl font-bold text-stone-900">{{ $card['value'] }}</p>
        </div>
    @endforeach
</div>

{{-- Recent additions + breed breakdown --}}
<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-xl border border-surface-border bg-surface shadow-sm">
        <h2 class="border-b border-surface-border px-4 py-3 font-semibold text-stone-900">
            Recently added
        </h2>

        @if ($recent->isEmpty())
            <div class="flex flex-col items-center gap-2 px-6 py-12 text-center">
                <x-icon name="inbox" class="h-8 w-8 text-stone-300" />
                <p class="text-sm text-stone-500">You have not added any chickens yet.</p>
                <x-button href="{{ route('chickens.create') }}" class="mt-2">Add your first chicken</x-button>
            </div>
        @else
            <ul class="divide-y divide-surface-border">
                @foreach ($recent as $chicken)
                    <li>
                        <a href="{{ route('chickens.show', $chicken) }}"
                            class="flex items-center gap-3 px-4 py-3 transition hover:bg-brand-50/50">
                            @if ($chicken->image)
                                <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}"
                                    loading="lazy" class="h-10 w-10 shrink-0 rounded-lg object-cover">
                            @else
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-400">
                                    <x-icon name="egg" class="h-5 w-5" />
                                </span>
                            @endif

                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-medium text-stone-900">{{ $chicken->name }}</span>
                                <span class="block text-xs text-stone-500">
                                    {{ $chicken->breed?->name ?? 'Unknown breed' }}
                                </span>
                            </span>

                            <span class="shrink-0 text-xs text-stone-400">
                                {{ $chicken->created_at->diffForHumans(short: true) }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="rounded-xl border border-surface-border bg-surface shadow-sm">
        <h2 class="border-b border-surface-border px-4 py-3 font-semibold text-stone-900">
            {{ auth()->user()->is_admin ? 'Breeds across all dens' : 'Your breeds' }}
        </h2>

        @if ($breedBreakdown->isEmpty())
            <div class="flex flex-col items-center gap-2 px-6 py-12 text-center">
                <x-icon name="book" class="h-8 w-8 text-stone-300" />
                <p class="text-sm text-stone-500">No chickens yet, so no breakdown to show.</p>
            </div>
        @else
            <x-egg-treemap :data="$breedBreakdown" />
        @endif
    </div>
</div>
