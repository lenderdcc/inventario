<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = Alerta::all();
        return view('alertas.index', compact('alertas'));
    }

    public function disparar(Request $request)
    {
        Alerta::create([
            'tipo' => $request->tipo,
            'mensaje' => $request->mensaje,
            'nivel' => $request->nivel,
            'estado' => 'Activa',
            'usuario_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', '⚠️ Alerta disparada correctamente');
    }
}
