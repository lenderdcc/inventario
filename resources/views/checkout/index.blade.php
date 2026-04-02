<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar compra - MIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <header class="bg-white shadow-md py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 rounded-full object-contain">
                <h1 class="text-2xl font-semibold text-blue-800">MIC Store</h1>
            </div>
            <a href="{{ route('inicio') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
               Volver a la tienda
            </a>
        </div>
    </header>

    <main class="flex-grow flex justify-center items-start mt-10 px-4">
        <div class="max-w-3xl w-full bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Confirmar compra</h2>

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <h3 class="font-medium text-gray-700">Resumen</h3>
                <ul class="mt-2 space-y-2">
                    @php $totalCalculado = 0; @endphp
                    @foreach($carrito as $item)
                        @php 
                            $subtotal = $item['precio'] * $item['cantidad']; 
                            $totalCalculado += $subtotal; 
                        @endphp
                        <li class="flex justify-between border-b border-gray-200 pb-2">
                            <div>
                                <strong>{{ $item['nombre'] }}</strong>
                                <span class="text-sm text-gray-500">x{{ $item['cantidad'] }}</span>
                            </div>
                            <div class="text-gray-700">$ {{ number_format($subtotal, 0, ',', '.') }}</div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 text-right">
                    <span class="text-lg font-semibold text-gray-800">Total: ${{ number_format($totalCalculado, 0, ',', '.') }}</span>
                </div>
            </div>

            <form id="compraForm" action="{{ route('compra.procesar') }}" method="POST" target="_blank">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                    <select name="metodo_pago" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                    <textarea name="descripcion" rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('inicio') }}" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium">
                        Seguir comprando
                    </a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow">
                        Pagar y generar factura
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-gray-500 text-sm py-6 mt-10">
        © {{ date('Y') }} MIC Store — Todos los derechos reservados
    </footer>

    <script>
        const form = document.getElementById('compraForm');
        
        form.addEventListener('submit', function (event) {
            // Mostramos el mensaje de éxito
            Swal.fire({
                title: '¡Procesando Compra!',
                text: 'Tu factura se abrirá en una nueva pestaña.',
                icon: 'success',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                // Después de dar click en OK, redirigimos la página principal a la tienda
                // para que el usuario no se quede en el checkout con el carrito vacío
                setTimeout(() => {
                    window.location.href = "{{ route('inicio') }}";
                }, 1000);
            });
            
            // El formulario se envía normalmente gracias al target="_blank"
            // Esto permite que el PDF fluya sin ser bloqueado por el script
        });
    </script>

</body>
</html>