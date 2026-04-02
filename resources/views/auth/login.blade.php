<x-layouts.custom-guest>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-blue-200 font-[Poppins]">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-30 h-20 mb-3 object-contain rounded-full">
            <h2 class="text-2xl font-bold text-blue-800">Bienvenidos a MIC</h2>
            <p class="text-gray-600 text-sm mt-1">Inicia sesión para continuar</p>
        </div>

        <!-- Contenedor del formulario -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl rounded-2xl">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Correo electrónico')" class="text-blue-800 font-semibold"/>
                    <x-text-input id="email" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                                  type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" class="text-blue-800 font-semibold"/>
                    <x-text-input id="password" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                                  type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                               name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Recuérdame') }}</span>
                    </label>
                </div>

                <!-- Botón e hipervínculos -->
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-blue-700 hover:text-blue-900"
                           href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif

                    <x-primary-button class="ml-3 bg-blue-700 hover:bg-blue-800 focus:ring-blue-500">
                        {{ __('Iniciar sesión') }}
                    </x-primary-button>
                </div>

                <!-- Registro -->
                <p class="text-center text-gray-600 text-sm mt-6">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="text-blue-700 hover:text-blue-900 font-semibold">
                        Regístrate aquí
                    </a>
                </p>
            </form>
        </div>
    </div>
</x-layouts.custom-guest>

