<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // Usamos la importación correcta
use Carbon\Carbon;

use App\Models\Producto;
use App\Models\Finanza;

class CompraController extends Controller
{
    public function checkout(Request $request)
    {
        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('inicio')->with('error', 'Tu carrito está vacío.');
        }

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('checkout.index', compact('carrito', 'total'));
    }

    public function procesarCompra(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,otro',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('inicio')->with('error', 'Carrito vacío.');
        }

        $userId = $request->user()->id;
        $metodoPago = $request->input('metodo_pago');
        $descripcion = $request->input('descripcion', null);
        $now = Carbon::now();
        
        // Generamos UNA SOLA referencia para toda la transacción
        $referenciaUnica = strtoupper(Str::random(10));

        DB::beginTransaction();
        try {
            $totalVenta = 0;
            $itemsParaFactura = [];

            foreach ($carrito as $item) {
                $producto = Producto::lockForUpdate()->find($item['id']);
                
                if (!$producto) {
                    throw new \Exception("Producto no encontrado: {$item['nombre']}");
                }

                if ($producto->stock_actual < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock_actual}");
                }

                // Descontar stock
                $producto->stock_actual -= $item['cantidad'];
                $producto->save();

                // Registrar en la tabla de finanzas
                Finanza::create([
                    'producto_id'     => $producto->id,
                    'user_id'         => $userId,
                    'metodo_pago'     => $metodoPago,
                    'estado_pago'     => 'completado',
                    'referencia_pago' => $referenciaUnica, // Todos los productos llevan la misma referencia
                    'descripcion'     => $descripcion,
                    'fecha_procesado' => $now,
                ]);

                $totalVenta += $item['precio'] * $item['cantidad'];
                
                // Guardamos el item con sus datos actuales para el PDF
                $itemsParaFactura[] = [
                    'nombre'   => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio'   => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad']
                ];
            }

            DB::commit();

            // Preparamos los datos EXACTOS que la vista necesita
            $dataFactura = [
                'user'              => $request->user(),
                'items'             => $itemsParaFactura, // Lista completa de productos
                'total'             => $totalVenta,
                'fecha'             => $now->format('d/m/Y H:i'),
                'referencia_compra' => $referenciaUnica,
                'metodo_pago'       => $metodoPago
            ];

            // Limpiar carrito
            session()->forget('carrito');

            // Cargar la vista del PDF
            $pdf = Pdf::loadView('invoices.factura', $dataFactura);
            
            return $pdf->stream("Factura_MIC_{$referenciaUnica}.pdf");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
    }
}