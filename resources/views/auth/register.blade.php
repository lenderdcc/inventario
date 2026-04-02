<x-layouts.custom-guest>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 font-[Poppins]">

        <!-- Logo y encabezado -->
        <div class="flex flex-col items-center mb-6 text-center">
           <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-30 h-20 mb-3 object-contain rounded-full">
            <h2 class="text-2xl font-bold text-blue-800">Crea tu cuenta en MIC</h2>
            <p class="text-gray-600 text-sm mt-1">Regístrate para comenzar</p>
        </div>

        <!-- Contenedor del formulario -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white/80 backdrop-blur-lg shadow-2xl rounded-2xl border border-blue-100">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre -->
                <div>
                    <x-input-label for="name" :value="__('Nombre completo')" class="text-blue-800 font-semibold"/>
                    <x-text-input id="name" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                                  type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Correo -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Correo electrónico')" class="text-blue-800 font-semibold"/>
                    <x-text-input id="email" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                                  type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" class="text-blue-800 font-semibold"/>
                    <x-text-input id="password" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                                  type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirmar Contraseña -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="text-blue-800 font-semibold"/>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                                  type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Botón y enlace -->
                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-blue-700 hover:text-blue-900"
                       href="{{ route('login') }}">
                        {{ __('¿Ya tienes una cuenta? Inicia sesión') }}
                    </a>

                    <x-primary-button
                        class="ml-3 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-semibold shadow-lg hover:shadow-xl focus:ring-4 focus:ring-blue-300 transition-all duration-200">
                        {{ __('Registrarme') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        
    </div>
</x-layouts.custom-guest>
