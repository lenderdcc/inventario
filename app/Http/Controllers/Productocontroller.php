<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StockBajoNotification;

class ProductoController extends Controller
{
    /**
     * Aumenta el stock de un producto específico.
     */
    public function updateStock(Request $request, $id)
    {
        // 1. Validamos que la cantidad sea un número válido
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        // 2. Buscamos el producto en la base de datos
        $producto = Producto::findOrFail($id);

        // 3. Sumamos la cantidad al stock actual
        $producto->stock_actual += $request->cantidad;

        // 4. Guardamos los cambios
        $producto->save();

        // 5. Retornamos con un mensaje de éxito
        return back()->with('success', '¡Stock de ' . $producto->nombre . ' aumentado correctamente!');
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'referencia' => 'required|unique:productos|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo_alerta' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $producto = Producto::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        return redirect()->route('dashboard');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'referencia' => 'required|max:255|unique:productos,referencia,' . $producto->id,
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo_alerta' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $user = Auth::user();

        if (!$user->hasRole('Supervisor')) {
            unset($data['stock_minimo_alerta']);
        }

        $producto->update($data);

        if ($producto->stock_actual <= $producto->stock_minimo_alerta) {
            $admins = User::role('Supervisor')->get();
            Notification::send($admins, new StockBajoNotification($producto));
        }

        return redirect()->route('dashboard')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('dashboard')->with('success', 'Producto eliminado exitosamente.');
    }
}