<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Dos Pasos (2FA)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-8">
        
        <!-- Ícono -->
        <div class="flex justify-center mb-6">
            <div class="bg-blue-100 p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 11c0-1.104.896-2 2-2s2 .896 2 2m-4 0a2 2 0 104 0m-2 0v2m-6-6V7a4 4 0 118 0v2m-1 0H9a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2v-8a2 2 0 00-2-2z" />
                </svg>
            </div>
        </div>

        <!-- Título -->
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Autenticación en Dos Pasos</h2>
        <p class="text-gray-600 text-center mb-6">
            Ingresa el código generado por tu aplicación de autenticación.
        </p>

        <!-- Errores -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Código TOTP</label>
                <input id="code" type="text" name="code" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-700"
                    placeholder="Ejemplo: 123456">

                @if ($errors->has('code'))
                    <p class="text-red-600 text-sm mt-2">{{ $errors->first('code') }}</p>
                @endif
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md">
                Verificar Código
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            ¿Problemas con tu código? <a href="#" class="text-blue-600 hover:underline">Contacta al administrador</a>.
        </p>
    </div>

</body>
</html>
