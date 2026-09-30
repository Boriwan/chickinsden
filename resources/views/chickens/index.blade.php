<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-stone-900">My chickens</h1>

            <x-button href="{{ route('chickens.create') }}">
                <x-icon name="plus" class="h-4 w-4" />
                Add chicken
            </x-button>
        </div>

        @if ($chickens->isEmpty())
            <div class="flex flex-col items-center gap-4 rounded-xl border border-dashed border-surface-border bg-surface px-6 py-16 text-center">
                <x-icon name="inbox" class="h-12 w-12 text-stone-300" />
                <div>
                    <p class="font-semibold text-stone-900">No chickens yet</p>
                    <p class="text-sm text-stone-500">Add your first chicken to start tracking them.</p>
                </div>
                <x-button href="{{ route('chickens.create') }}">
                    <x-icon name="plus" class="h-4 w-4" />
                    Add chicken
                </x-button>
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
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-site-layout>
