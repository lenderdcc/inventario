<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4 text-black">¡Bienvenido, {{ auth()->user()->name }}!</h3>
                    <p class="mb-2 text-black">Este es el panel exclusivo para administradores.</p>
                    <p class="text-black"><strong>Rol:</strong> {{ auth()->user()->roles->first()->name ?? 'Sin rol' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>