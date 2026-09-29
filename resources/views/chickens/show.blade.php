<x-site-layout>
    <div class="mx-auto max-w-4xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <x-button color="ghost" onclick="window.history.back()">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Back
            </x-button>

            <x-button href="{{ route('admin.chickens.edit', $chicken) }}">
                <x-icon name="pencil" class="h-4 w-4" />
                Edit
            </x-button>

            <form action="{{ route('admin.chickens.destroy', $chicken) }}" method="POST">
                @method('DELETE')
                @csrf

                <x-button type="submit" color="red">
                    <x-icon name="trash" class="h-4 w-4" />
                    Delete
                </x-button>
            </form>
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-3">
            <h1 class="text-3xl font-bold text-stone-900">{{ $chicken->name }}</h1>

            <span @class([
                'rounded-full px-2.5 py-1 text-xs font-semibold',
                'bg-gender-male/10 text-gender-male' => $chicken->gender === 'male',
                'bg-gender-female/10 text-gender-female' => $chicken->gender === 'female',
            ])>
                {{ $chicken->gender === 'male' ? '♂ Male' : '♀ Female' }}
            </span>
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
                            <a href="{{ route('breeds.show', $chicken->breed_id) }}"
                                class="text-brand-600 hover:underline">
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
