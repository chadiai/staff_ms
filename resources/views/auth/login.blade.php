<x-login-register.layout>
    <x-slot name="description">login</x-slot>
    <x-slot name="title">Login</x-slot>

    <x-tmk.section class="grid grid-cols-1 max-w-md m-auto">
        {{-- show validation errors --}}
        <x-validation-errors class="mb-4"/>

        <img class="text-center ml-auto mr-auto" src="{{ asset('images/logo-staff-management-system.svg') }}" alt="logo"
             width="70">
        {{-- login form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Username or email') }}"/>
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                         required autofocus autocomplete="name"/>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}"/>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                         autocomplete="current-password"/>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember"/>
                    <span class="ml-2 text-md text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-md text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-4 justify-end">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-tmk.section>
</x-login-register.layout>

{{--
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('storage/logo/logo-staff-management-system.svg') }}" alt="SVG logo">
        </x-slot>
        <img src="{{ asset('storage/logo/logo-staff-management-system.svg') }}" alt="SVG logo">

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-bold text-sm text-red-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
--}}
