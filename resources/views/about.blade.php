<x-site-layout>
    <div class="mx-auto max-w-3xl px-5 py-16 text-center">
        <div class="mb-6 flex items-center justify-center gap-3">
            <x-icon name="info" class="h-9 w-9 text-brand-500" />
            <h1 class="text-4xl font-bold text-stone-900">About Chickins Den</h1>
        </div>

        <p class="mb-4 text-lg text-stone-600">
            A simple web application for logging and managing your chickens. You can create, view, and
            manage your chickens with ease.
        </p>

        <p class="mb-10 text-left leading-relaxed text-stone-500">
            Every chicken gets a profile with a photo, age, breed and traits, so you always have the details
            of your den at a glance. Admins can manage all chickens, breeds and traits from one place.
        </p>

        <img src="{{ asset('imgs/ChickinsLogo.png') }}" alt="Chickins Den" class="mx-auto h-40 w-40 object-contain">
    </div>
</x-site-layout>
