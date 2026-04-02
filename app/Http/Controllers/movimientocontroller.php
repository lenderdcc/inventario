<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoInventario::with('producto')->get();
        return view('movimientos.index', compact('movimientos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo_movimiento' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
        ]);

        MovimientoInventario::create([
            'producto_id' => $request->producto_id,
            'tipo_movimiento' => $request->tipo_movimiento,
            'cantidad' => $request->cantidad,
            'fecha' => now(),
            'usuario_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Movimiento registrado con éxito.');
    }
}
