<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <x-page-heading icon="{{ $filters ? 'tag' : 'egg' }}" heading-class="">
                    @if ($filters)
                        Filtered chickens
                    @else
                        My chickens
                    @endif
                </x-page-heading>

                {{-- Each chip clears only its own filter, so the rest stay applied. --}}
                @if ($filters)
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @foreach ($filters as $key => $value)
                            @php
                                $label = match ($key) {
                                    'gender' => ucfirst($value),
                                    default => $value->name,
                                };

                                // A missing key has to be guarded before the
                                // nullsafe operator, which only covers a present
                                // value that happens to be null.
                                $keepGender = $key === 'gender' ? null : ($filters['gender'] ?? null);
                                $keepBreed = $key === 'breed' ? null : (($filters['breed'] ?? null)?->id);
                                $keepTrait = $key === 'trait' ? null : (($filters['trait'] ?? null)?->id);
                            @endphp

                            <a href="{{ route('chickens.index', array_filter([
                                'gender' => $keepGender,
                                'breed' => $keepBreed,
                                'trait' => $keepTrait,
                            ])) }}"
                                class="inline-flex items-center gap-1.5 rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold text-brand-800 transition hover:bg-brand-200 hover:underline">
                                {{ $label }}

                                <span aria-hidden="true" class="text-sm leading-none">&times;</span>
                                <span class="sr-only">Remove this filter</span>
                            </a>
                        @endforeach

                        <a href="{{ route('chickens.index') }}"
                            class="text-sm font-medium text-brand-600 hover:underline">
                            Clear all
                        </a>
                    </div>
                @endif
            </div>

            <x-button href="{{ route('chickens.create') }}">
                <x-icon name="plus" class="h-4 w-4" />
                Add chicken
            </x-button>
        </div>

        @if ($chickens->isEmpty())
            <div class="flex flex-col items-center gap-4 rounded-xl border border-dashed border-surface-border bg-surface px-6 py-16 text-center">
                <x-icon name="inbox" class="h-12 w-12 text-stone-300" />
                <div>
                    <p class="font-semibold text-stone-900">
                        {{ $filters ? 'No chickens match these filters' : 'No chickens yet' }}
                    </p>
                    <p class="text-sm text-stone-500">
                        @if ($filters)
                            Try removing a filter, or clear them all to see everything.
                        @else
                            Add your first chicken to start tracking them.
                        @endif
                    </p>
                </div>

                @if ($filters)
                    <x-button color="gray" href="{{ route('chickens.index') }}">Clear all filters</x-button>
                @else
                    <x-button href="{{ route('chickens.create') }}">
                        <x-icon name="plus" class="h-4 w-4" />
                        Add chicken
                    </x-button>
                @endif
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($chickens as $chicken)
                    <a href="{{ route('chickens.show', $chicken) }}"
                        class="group flex flex-col overflow-hidden rounded-xl border border-surface-border bg-surface shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        @if ($chicken->image)
                            <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}"
                                loading="lazy"
                                class="h-48 w-full object-cover">
                        @else
                            <div class="flex h-48 w-full items-center justify-center bg-stone-100 text-stone-400">
                                <x-icon name="egg" class="h-10 w-10" />
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col gap-2 p-4">
                            <div class="flex items-center justify-between gap-2">
                                <h2 class="text-lg font-bold text-stone-900">{{ $chicken->name }}</h2>

                                <span @class([
                                    'shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold',
                                    'bg-gender-male/10 text-gender-male' => $chicken->gender === 'male',
                                    'bg-gender-female/10 text-gender-female' => $chicken->gender === 'female',
                                ])>
                                    {{ $chicken->gender === 'male' ? '♂ Male' : '♀ Female' }}
                                </span>
                            </div>

                            <p class="text-sm text-stone-500">
                                {{ $chicken->breed?->name ?? 'Unknown breed' }} &middot;
                                {{ \Carbon\Carbon::parse($chicken->birth_date)->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                                old
                            </p>

                            @if ($chicken->traits->isNotEmpty())
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    @foreach ($chicken->traits as $trait)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-brand-100 px-2 py-0.5 text-xs font-semibold text-brand-800">
                                            <x-icon :name="$trait->iconOrDefault()" class="h-3 w-3 shrink-0" />

                                            {{ $trait->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if ($chickens->hasPages())
            <div class="mt-6">
                {{ $chickens->links() }}
            </div>
        @endif
    </div>
</x-site-layout>
