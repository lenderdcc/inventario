<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Inventario - MIC Tecnología</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F3F4F6; }
        .text-primary { color: #2563EB; }
        .bg-primary { background-color: #2563EB; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                Bienvenido, <span class="text-primary">{{ Auth::user()->name }}</span>
            </h1>

            <div class="flex items-center gap-4" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" class="relative focus:outline-none text-gray-700 hover:text-primary transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3c0 .386-.146.735-.395 1.005L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" x-transition @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50" style="display: none;">
                        <div class="p-3 border-b text-gray-700 font-semibold">Notificaciones</div>
                        @forelse (Auth::user()->unreadNotifications as $notification)
                            <div class="px-4 py-3 hover:bg-gray-100 border-b">
                                <p class="text-sm text-gray-800">{{ $notification->data['mensaje'] ?? 'Stock bajo.' }}</p>
                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">No hay nuevas notificaciones 🎉</div>
                        @endforelse
                        <div class="p-3 text-center">
                            <form action="{{ route('notificaciones.leer') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-primary font-semibold text-sm hover:underline">Marcar todas como leídas</button>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="bg-primary text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-sm shadow-md">Mi Perfil</a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 bg-red-500 px-4 py-2 rounded-lg text-white font-semibold hover:bg-red-600 transition shadow-md">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 py-8">
        <div x-data="stockModals()" class="max-w-7xl mx-auto px-6 space-y-8">
            
            @php
                $criticos = $productos->filter(fn($p) => $p->stock_actual <= $p->stock_minimo_alerta);
                $critico_count = $criticos->count();
                $normal_count = $productos->total() - $critico_count;
            @endphp

            @if(auth()->user()->hasRole('Supervisor'))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white border-l-4 border-blue-500 rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500 uppercase font-semibold">Total Productos</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $productos->total() }}</h3>
                </div>
                <div class="bg-white border-l-4 border-red-500 rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500 uppercase font-semibold">Stock Crítico</p>
                    <h3 class="text-3xl font-extrabold text-red-600 mt-2">{{ $critico_count }}</h3>
                </div>
                <div class="bg-white border-l-4 border-green-500 rounded-xl shadow-md p-6">
                    <p class="text-sm text-gray-500 uppercase font-semibold">Stock Normal</p>
                    <h3 class="text-3xl font-extrabold text-green-600 mt-2">{{ $normal_count }}</h3>
                </div>
            </div>
            @endif

            <div x-data="productoModal()" class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Inventario de Productos</h3>
                        <p class="text-sm text-gray-500">Listado de todos los productos registrados.</p>
                    </div>
                    @if(auth()->user()->hasRole('Supervisor'))
                    <a href="{{ route('productos.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 hover:bg-blue-700 transition">
                        + Añadir Producto
                    </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase">Referencia</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase">Precio</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase">Stock</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-700 uppercase">Ver</th>
                                @if(auth()->user()->hasRole('Supervisor'))
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-center font-semibold text-gray-700 uppercase">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($productos as $producto)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $producto->nombre }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $producto->referencia }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <span class="text-lg font-bold {{ $producto->stock_actual <= $producto->stock_minimo_alerta ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $producto->stock_actual }}
                                        </span>
                                        
                                        @if(auth()->user()->hasRole('Supervisor'))
                                            <form action="{{ route('productos.updateStock', $producto->id) }}" method="POST" class="flex items-center bg-gray-100 p-1 rounded-lg border border-gray-200 shadow-sm">
                                                @csrf
                                                <input type="number" name="cantidad" min="1" value="1" 
                                                       class="w-14 px-2 py-1 text-sm border-none bg-transparent text-center focus:ring-0 font-semibold"
                                                       title="Cantidad a sumar">
                                                
                                                <button type="submit" title="Aumentar Stock" 
                                                        class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded-md transition-all transform hover:scale-110 active:scale-95 shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button @click="openProducto({
                                        nombre: '{{ $producto->nombre }}',
                                        referencia: '{{ $producto->referencia }}',
                                        precio: '{{ number_format($producto->precio, 0, ',', '.') }}',
                                        stock: '{{ $producto->stock_actual }}',
                                        fecha: '{{ $producto->fecha_caducidad ?? 'No registrada' }}',
                                        imagen: '{{ $producto->imagen ? asset('storage/' . $producto->imagen) : '' }}'
                                    })" class="text-purple-600 hover:text-purple-900 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>

                                @if(auth()->user()->hasRole('Supervisor'))
                                <td class="px-6 py-4">
                                    <span class="{{ $producto->stock_actual <= $producto->stock_minimo_alerta ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $producto->stock_actual <= $producto->stock_minimo_alerta ? 'Crítico' : 'Normal' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('productos.edit', $producto) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Editar</a>
                                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 border-t">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>

        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" x-transition style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md relative shadow-2xl">
                <button @click="close()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Detalles del Producto</h2>
                
                <template x-if="producto.imagen">
                    <div class="mb-6 flex justify-center">
                        <img :src="producto.imagen" class="w-48 h-48 object-contain rounded-xl border p-2 shadow-sm bg-gray-50">
                    </div>
                </template>
                
                <div class="space-y-4 text-gray-700">
                    <div class="flex justify-between border-b pb-2"><strong>Nombre:</strong> <span x-text="producto.nombre"></span></div>
                    <div class="flex justify-between border-b pb-2"><strong>Referencia:</strong> <span x-text="producto.referencia"></span></div>
                    <div class="flex justify-between border-b pb-2"><strong>Precio:</strong> <span class="text-green-600 font-bold">$<span x-text="producto.precio"></span></span></div>
                    <div class="flex justify-between border-b pb-2"><strong>Stock actual:</strong> <span class="font-bold" x-text="producto.stock"></span></div>
                    <div class="flex justify-between"><strong>Fecha:</strong> <span x-text="producto.fecha"></span></div>
                </div>
                
                <button @click="close()" class="mt-8 w-full bg-gray-800 hover:bg-gray-900 text-white py-3 rounded-xl font-bold transition shadow-lg">Entendido</button>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function stockModals() {
            return {
                modalCritico: false,
                modalNormal: false,
                openCritico() { this.modalCritico = true },
                openNormal() { this.modalNormal = true }
            }
        }
        function productoModal() {
            return {
                open: false,
                producto: {},
                openProducto(data) { this.producto = data; this.open = true; },
                close() { this.open = false; }
            };
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "¡Hecho!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#2563EB",
                timer: 3000
            });
        </script>
    @endif
</body>
</html>