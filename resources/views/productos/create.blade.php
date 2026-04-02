<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F3F4F6; }
        .text-primary { color: #2563EB; }
        .bg-primary { background-color: #2563EB; }
        .hover\:bg-secondary:hover { background-color: #1E40AF; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Título -->
            <h1 class="text-xl font-semibold text-blue-600">Agregar Nuevo Producto</h1>

            <div class="flex items-center gap-4">
                <!-- Botón Cerrar Sesión -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 bg-red-500 px-4 py-2 rounded-lg 
                                   text-white font-semibold hover:bg-red-600 
                                   transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    <main class="flex-grow py-10">
        <div class="max-w-3xl mx-auto px-6">

            <!-- CARD -->
            <div class="bg-white shadow-2xl rounded-2xl p-8 border border-blue-100">
                <!-- Encabezado -->
                <div class="mb-6 pb-4 border-b border-blue-200">
                    <h3 class="text-2xl font-bold text-blue-700">Agregar Nuevo Producto</h3>
                    <p class="text-sm text-gray-600 mt-1">Completa la información del nuevo producto</p>
                </div>

                <!-- Errores de validación -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <h4 class="text-red-800 font-semibold">Por favor corrige los siguientes errores:</h4>
                        </div>
                        <ul class="list-disc pl-7 text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORMULARIO -->
                <form action="{{ route('productos.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Nombre y Referencia -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del producto</label>
                            <input name="nombre" value="{{ old('nombre') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 px-4 py-3 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-all duration-200 bg-white" 
                                   placeholder="Ej: Laptop HP" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Referencia</label>
                            <input name="referencia" value="{{ old('referencia') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 px-4 py-3 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-all duration-200 bg-white" 
                                   placeholder="Ej: REF-001" />
                        </div>
                    </div>

                    <!-- Precio, Stock Actual, Stock Mínimo -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Precio</label>
                            <input name="precio" type="number" step="0.01" value="{{ old('precio') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 px-4 py-3 
                                          focus:ring-2 focus:ring-green-500 focus:border-green-500 
                                          transition-all duration-200 bg-white" 
                                   placeholder="0.00" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Actual</label>
                            <input name="stock_actual" type="number" value="{{ old('stock_actual') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 px-4 py-3 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-all duration-200 bg-white" 
                                   placeholder="0" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Mínimo</label>
                            <input name="stock_minimo_alerta" type="number" value="{{ old('stock_minimo_alerta') }}" required
                                   class="block w-full rounded-lg border-2 border-gray-300 px-4 py-3 
                                          focus:ring-2 focus:ring-orange-500 focus:border-orange-500 
                                          transition-all duration-200 bg-white" 
                                   placeholder="0" />
                        </div>
                    </div>

                    <!-- Fecha de Caducidad -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Caducidad (opcional)</label>
                        <input name="fecha_caducidad" type="date" value="{{ old('fecha_caducidad') }}"
                               class="block w-full rounded-lg border-2 border-gray-300 px-4 py-3 
                                      focus:ring-2 focus:ring-purple-500 focus:border-purple-500 
                                      transition-all duration-200 bg-white" />
                    </div>
                   
                    <!-- Botones -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('productos.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 
                                  border-2 border-gray-300 rounded-lg 
                                  text-gray-700 font-semibold
                                  hover:bg-gray-50 hover:border-gray-400
                                  transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancelar
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-8 py-3 
                                       text-white font-bold text-base
                                       bg-gradient-to-r from-green-600 to-green-500 
                                       hover:from-green-700 hover:to-green-600
                                       rounded-lg shadow-lg hover:shadow-xl 
                                       transition-all duration-200 
                                       transform hover:scale-105
                                       focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white text-blue text-center py-4 mt-10 text-sm">
        &copy; {{ date('Y') }} MIC Inventario - Todos los derechos reservados.
    </footer>
</body>
</html>
