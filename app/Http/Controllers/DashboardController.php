<?php

namespace App\Http\Controllers;

use App\Models\Producto; // Asegúrate de que este sea el nombre correcto de tu modelo
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard con el inventario de productos.
     */
    public function index()
    {
        
        // 1. Obtener los productos y paginarlos (por ejemplo, 10 por página)
        // Usamos el método paginate() para que la vista pueda usar $productos->links()
        $productos = Producto::paginate(10);
        
        // 2. Devolver la vista 'dashboard.blade.php' y pasarle la colección de productos.
        // La vista utiliza $productos (paginada) para la tabla y las tarjetas de estadísticas.
        return view('dashboard', compact('productos'));
    }
}
