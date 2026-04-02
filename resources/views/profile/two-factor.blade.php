<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Autenticación en dos pasos (2FA)
        </h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        @if (session('success'))
            <div style="color: green; font-weight: bold;">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div style="color: red; font-weight: bold;">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-bold mb-4">Escanea este código QR con Google Authenticator</h3>
            <div class="mb-4">{!! $QR_Image !!}</div>

            <form method="POST" action="{{ route('two-factor.confirm') }}">
                @csrf
                <label for="code">Introduce el código de 6 dígitos:</label>
                <input type="text" name="code" id="code" required class="border rounded p-2 w-full mt-2 mb-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Verificar código</button>
            </form>

            <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-4">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Desactivar 2FA</button>
            </form>
        </div>
    </div>
</x-app-layout>
