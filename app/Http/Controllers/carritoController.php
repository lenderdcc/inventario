<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function agregar($id)
{
    $producto = Producto::findOrFail($id);
    $carrito = session()->get('carrito', []);

    // Si ya existe el producto en el carrito, aumentamos cantidad
    if (isset($carrito[$id])) {
        $carrito[$id]['cantidad']++;
    } else {
        $carrito[$id] = [
            "id" => $producto->id,
            "nombre" => $producto->nombre,
            "precio" => $producto->precio,
            "cantidad" => 1,
            "imagen" => $producto->imagenes ?? 'default.png'  // ← SIEMPRE GUARDA IMAGEN
        ];
    }

    session()->put('carrito', $carrito);

    return redirect()->back()->with('success', 'Producto añadido al carrito.');
}

    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    public function limpiar()
    {
        session()->forget('carrito');
        return redirect()->back()->with('success', 'Carrito vacío.');
    }
}
