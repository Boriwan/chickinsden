<x-site-layout>
    <div class="mx-auto max-w-3xl px-5 py-16 text-center">
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

        <x-button href="{{ route('chickens.index') }}">
            Show my chickens
            <x-icon name="arrow-left" class="h-4 w-4 rotate-180" />
        </x-button>
    </div>
</x-site-layout>
