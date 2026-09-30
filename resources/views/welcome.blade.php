<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-10">
        @auth
            <div class="mb-8 text-center">
                <div class="mb-3 flex items-center justify-center gap-3">
                    <x-icon name="egg" class="h-9 w-9 text-brand-500" />
                    <h1 class="text-4xl font-bold text-stone-900">
                        Welcome back, {{ str(auth()->user()->name)->explode(' ')->first() }}
                    </h1>
                </div>

                <p class="text-lg text-stone-600">Here is what is happening with your flock.</p>
            </div>

            <x-dashboard :stats="$stats" :recent="$recent" :breed-breakdown="$breedBreakdown" />
        @else
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-6 flex items-center justify-center gap-3">
                    <x-icon name="egg" class="h-10 w-10 text-brand-500" />
                    <h1 class="text-4xl font-bold text-stone-900">Welcome to Chickins Den</h1>
                </div>

                <p class="mb-4 text-lg text-stone-600">
                    A simple web application for logging and managing your chickens. You can create, view, and
                    manage your chickens with ease.
                </p>

                <p class="mb-10 text-left leading-relaxed text-stone-500">
                    Every chicken gets a profile with a photo, age, breed and traits, so you always have the details
                    of your flock at a glance. Admins can manage all chickens, breeds and traits from one place.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <x-button href="{{ route('login') }}">
                        <x-icon name="user" class="h-4 w-4" />
                        Log in
                    </x-button>

                    <x-button color="gray" href="{{ route('register') }}">Create an account</x-button>
                </div>
            </div>
        @endauth
    </div>
</x-site-layout>
