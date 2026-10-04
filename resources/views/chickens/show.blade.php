<x-site-layout>
    <div class="mx-auto max-w-4xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <x-button color="ghost" onclick="window.history.back()">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Back
            </x-button>

            @can('update', $chicken)
                <x-button href="{{ route('chickens.edit', $chicken) }}">
                    <x-icon name="pencil" class="h-4 w-4" />
                    Edit
                </x-button>

                <form action="{{ route('chickens.destroy', $chicken) }}" method="POST"
                    onsubmit="return confirm('Delete {{ $chicken->name }}? This cannot be undone.')">
                    @method('DELETE')
                    @csrf

                    <x-button type="submit" color="red">
                        <x-icon name="trash" class="h-4 w-4" />
                        Delete
                    </x-button>
                </form>
            @endcan
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-3">
            <x-page-heading icon="egg" heading-class="">{{ $chicken->name }}</x-page-heading>

            <span @class([
                'rounded-full px-2.5 py-1 text-xs font-semibold',
                'bg-gender-male/10 text-gender-male' => $chicken->gender === 'male',
                'bg-gender-female/10 text-gender-female' => $chicken->gender === 'female',
            ])>
                {{ $chicken->gender === 'male' ? '♂ Male' : '♀ Female' }}
            </span>

            {{-- Each trait links to the chicken list filtered to that trait. --}}
            @foreach ($chicken->traits as $trait)
                <a href="{{ route('chickens.index', ['trait' => $trait->id]) }}"
                    title="See every chicken with the {{ $trait->name }} trait"
                    class="rounded-full bg-brand-100 px-2.5 py-1 text-xs font-semibold text-brand-800 underline-offset-2 transition hover:bg-brand-200 hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-800">
                    <x-icon :name="$trait->iconOrDefault()" class="h-3 w-3 shrink-0" />

                    {{ $trait->name }}
                </a>
            @endforeach

        </div>

        <div class="grid items-start gap-6 sm:grid-cols-[16rem_1fr]">
            <div>
                @if ($chicken->image)
                    <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}"
                        class="aspect-square w-full rounded-xl border border-surface-border object-cover shadow-sm">
                @else
                    <div
                        class="flex aspect-square w-full items-center justify-center rounded-xl border border-dashed border-surface-border bg-surface text-stone-300">
                        <x-icon name="egg" class="h-12 w-12" />
                    </div>
                @endif
            </div>

            <div>
                <dl class="divide-y divide-surface-border rounded-xl border border-surface-border bg-surface shadow-sm">
                    <div class="flex items-center justify-between gap-4 p-4">
                        <dt class="text-sm text-stone-500">Breed</dt>
                        <dd class="font-medium">
                            {{-- Same pill as the Breeds stat card, minus the icon. --}}
                            <a href="{{ route('breeds.show', $chicken->breed_id) }}"
                                class="inline-block rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-200 hover:underline">
                                {{ $chicken->breed?->name ?? 'Unknown' }}
                            </a>
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 p-4">
                        <dt class="text-sm text-stone-500">Age</dt>
                        <dd class="font-medium">
                            {{ \Carbon\Carbon::parse($chicken->birth_date)->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 p-4">
                        <dt class="text-sm text-stone-500">Birth date</dt>
                        <dd class="font-medium">
                            {{ \Carbon\Carbon::parse($chicken->birth_date)->format('d.m.Y') }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 p-4">
                        <dt class="text-sm text-stone-500">Height</dt>
                        <dd class="font-medium">{{ $chicken->height }} cm</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4 p-4">
                        <dt class="text-sm text-stone-500">Weight</dt>
                        <dd class="font-medium">{{ ucfirst($chicken->weight) }}</dd>
                    </div>


                </dl>
            </div>
        </div>
    </div>
</x-site-layout>
