<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
 public function index(Request $request)
{
    $search = $request->input('search');

    $productos = Producto::when($search, function ($query) use ($search) {
        return $query->where('nombre', 'like', "%{$search}%")
                     ->orWhere('referencia', 'like', "%{$search}%");
    })->paginate(12);

    return view('welcome', compact('productos', 'search'));
}


}
