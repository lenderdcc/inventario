<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MIC Inventario') }}</title>

    <!-- Fuente moderna (Inter - más profesional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Scripts y estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 via-blue-50 to-gray-100 min-h-screen">

    <div class="min-h-screen flex flex-col">

        <!-- Navbar principal -->
        @include('layouts.navigation')

        <!-- Encabezado de página -->
        @if (isset($header))
            <header class="bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 shadow-xl">
                <div class="max-w-7xl mx-auto py-8 px-6 sm:px-8 lg:px-10">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Contenido principal -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer mejorado -->
        <footer class="bg-white/80 backdrop-blur-sm shadow-inner mt-auto border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600">
                        © {{ date('Y') }} <span class="font-semibold text-blue-600">MIC Inventario</span> — Todos los derechos reservados.
                    </p>
                    <div class="flex gap-4 text-xs text-gray-500">
                        <a href="#" class="hover:text-blue-600 transition-colors">Ayuda</a>
                        <span>•</span>
                        <a href="#" class="hover:text-blue-600 transition-colors">Soporte</a>
                        <span>•</span>
                        <a href="#" class="hover:text-blue-600 transition-colors">Documentación</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts de notificaciones -->
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Entendido',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Entendido'
            });
        @endif

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target;
            
            Swal.fire({
                title: '¿Estás seguro/a?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }
    </script>

</body>
</html>