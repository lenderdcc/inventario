<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC Tecnología - Tienda</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .text-primary { color: #2563EB; }
        .bg-primary { background-color: #2563EB; }
        .hover\:bg-secondary:hover { background-color: #1E40AF; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- 🔹 Encabezado -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between py-4 px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-26 rounded-full shadow-sm">
                <h1 class="text-2xl font-semibold text-primary">MIC Tecnología</h1>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Botón Carrito -->
                <button id="btnCarrito" class="relative p-2 hover:bg-blue-50 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 6.45A1 1 0 007.63 21h8.74a1 1 0 00.98-.8L19 13M7 13h10" />
                    </svg>
                    @if(session('carrito') && count(session('carrito')) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5">
                            {{ count(session('carrito')) }}
                        </span>
                    @endif
                </button>

                <a href="{{ route('login') }}"
                   class="bg-primary text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-secondary transition-all duration-300">
                   Iniciar sesión
                </a>
            </div>
        </div>
    </header>
  

<!-- 🔹 Barra de búsqueda -->
<!-- 🔹 Barra de búsqueda mejorada -->
<div class="bg-white py-6 shadow-md">
    <div class="max-w-5xl mx-auto px-6">
        <form action="{{ route('inicio') }}" method="GET"
            class="flex items-center gap-3 bg-gray-100 rounded-xl p-2 shadow-inner">

            <!-- Icono de lupa -->
            <div class="px-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-6 w-6 text-gray-500" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z" />
                </svg>
            </div>

            <!-- Campo de texto -->
            <input 
                type="text" 
                name="search" 
                placeholder="Buscar productos por nombre o referencia..." 
                value="{{ $search ?? '' }}"
                class="w-full bg-transparent focus:outline-none text-gray-700 placeholder-gray-400"
            >

            <!-- Botón -->
            <button 
                type="submit" 
                class="bg-primary text-white px-6 py-2 rounded-lg font-medium 
                       hover:bg-secondary transition-all shadow-md hover:shadow-lg">
                Buscar
            </button>
        </form>
    </div>
</div>


    <!-- 🔹 Catálogo de Productos -->
    <main class="max-w-7xl mx-auto py-12 px-6">
        
        <h2 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Catálogo de Productos</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
         @foreach ($productos as $producto)
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">

        <img 
            src="{{ asset('/images/productos/' . $producto->imagenes) }}" 
            alt="{{ $producto->nombre }}" 
            class="w-full h-48 object-cover"
        >

        <div class="p-5">
            <h3 class="text-lg font-semibold text-gray-800">{{ $producto->nombre }}</h3>
            <p class="text-gray-500 text-sm mt-1">Ref: {{ $producto->referencia }}</p>
            <p class="text-blue-600 font-semibold text-xl mt-3">$ {{ number_format($producto->precio, 0, ',', '.') }}</p>

            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-medium py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-600 transition-all duration-200">
                    Añadir al carrito
                </button>
            </form>
        </div>
    </div>
@endforeach

        </div>
    </main>

    <!-- 🔹 Fondo oscuro (overlay) -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"></div>

    <!-- 🔹 Carrito lateral -->
<div id="carritoDrawer" 
     class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl transform translate-x-full 
            transition-transform duration-300 z-50 overflow-y-auto rounded-l-2xl">

    <div class="p-5 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Tu carrito</h2>
        <button id="cerrarCarrito" class="text-gray-500 hover:text-red-500 text-2xl font-bold">&times;</button>
    </div>

    @if(session('carrito') && count(session('carrito')) > 0)

<div class="p-5 space-y-4">

    @php $total = 0; @endphp

    @foreach(session('carrito') as $item)

        @php 
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;
        @endphp

        <div class="flex items-center gap-3 border-b pb-3">

            <!-- Imagen del producto -->
            <img 
                src="{{ isset($item['imagen']) ? asset('/images/productos/' . $item['imagen']) : asset('images/productos/default.png') }}" 
                alt="{{ $item['nombre'] }}"
                class="w-16 h-16 object-cover rounded-lg shadow-sm"
            >

            <!-- Info -->
            <div class="flex-1">
                <p class="font-medium text-gray-800">{{ $item['nombre'] }}</p>
                <p class="text-sm text-gray-500">x{{ $item['cantidad'] }}</p>
            </div>

            <!-- Precio y eliminar -->
            <div class="text-right">

                <p class="font-semibold text-primary">
                    $ {{ number_format($subtotal, 0, ',', '.') }}
                </p>

                <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST" class="mt-1">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs text-red-500 hover:text-red-700">
                        Eliminar
                    </button>
                </form>
            </div>

        </div>

    @endforeach
</div>
                <div class="border-t pt-4 text-right">
                    <p class="text-lg font-semibold text-gray-800">Total:</p>
                    <p class="text-primary text-2xl font-bold">$ {{ number_format($total, 0, ',', '.') }}</p>
                    <!-- dentro del drawer, después del total -->
<form action="{{ route('checkout') }}" method="GET">
    <button type="submit" class="w-full mt-4 bg-blue-600 text-white py-2 rounded-lg">Comprar</button>
</form>

                </div>
            </div>
        @else
            <div class="p-6 text-center text-gray-500">Tu carrito está vacío 🛍️</div>
@endif
</div>

    <!-- 🔹 Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto py-6 text-center text-sm">
            © {{ date('Y') }} MIC Tecnología — Innovación para todos
        </div>
    </footer>

    <!-- 🔹 Script para abrir/cerrar el carrito -->
    <script>
        const btnCarrito = document.getElementById('btnCarrito');
        const carritoDrawer = document.getElementById('carritoDrawer');
        const cerrarCarrito = document.getElementById('cerrarCarrito');
        const overlay = document.getElementById('overlay');

        btnCarrito.addEventListener('click', () => {
            carritoDrawer.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
        });

        cerrarCarrito.addEventListener('click', () => {
            carritoDrawer.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay.addEventListener('click', () => {
            carritoDrawer.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

</body>
</html>
