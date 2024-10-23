<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>

    <div class="pb-12">
        <div class="py-6 px-6 rounded-md shadow-sm bg-white max-w-screen-xl mx-auto">
            <p class="mb-1 font-semibold">Transfers!</p>
            <p class="mb-3">Let's kick off a transfer for 10GBP</p>

            <form method="post" action="{{ route('stripe.transfer') }}">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Transfer</button>
            </form>

            <div class="mt-8">
                <p class="font-semibold text-xl mb-1">Payouts</p>
                <p class="mb-6">These are your payouts for the appointments you have fulfilled through our platform.</p>

                <div class="p-4 rounded-lg shadow-sm bg-slate-50 border border-slate-200">
                    <div id="container"></div>
                    <div id="error" hidden>Something went wrong!</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
