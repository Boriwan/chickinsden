<x-site-layout>
    <h1 class="m-5 text-3xl font-bold">My chickens🐓</h1>
    <hr>

    <div class="m-5 flex flex-wrap gap-5">
        @foreach ($chickens as $chicken)
            @php
                $tint = $chicken->gender === 'male' ? 'bg-tint-male' : 'bg-tint-female';
            @endphp
            <a href="{{ route('chickens.show', $chicken) }}"
                class="w-56 rounded-lg border border-surface-border p-5 transition hover:scale-105 {{ $tint }}">
                <h2 class="text-xl font-bold">
                    {{ $chicken->name }}
                    @if ($chicken->gender === 'male')
                        ♂
                    @else
                        ♀
                    @endif
                </h2>

                @if ($chicken->image)
                    <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}"
                        class="mx-auto my-3 h-48 w-48 rounded-lg object-cover">
                @else
                    <div class="my-3 flex h-48 w-48 items-center justify-center rounded-lg bg-gray-200 text-gray-400">
                        No image
                    </div>
                @endif

                <ul class="list-none p-0">
                    <li>Age: {{ \Carbon\Carbon::parse($chicken->birth_date)->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}</li>
                </ul>
            </a>
        @endforeach
    </div>
</x-site-layout>
