<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Producto;

class AlertaStockTest extends TestCase
{
    /** @test */
    public function alerta_se_activa_cuando_stock_es_igual_o_menor_al_minimo()
    {
        $producto = new Producto([
            'stock_actual' => 5,
            'stock_minimo_alerta' => 5,
        ]);

        $this->assertTrue($producto->tieneAlertaStock());
    }

    /** @test */
    public function alerta_no_se_activa_cuando_stock_es_mayor_al_minimo()
    {
        $producto = new Producto([
            'stock_actual' => 10,
            'stock_minimo_alerta' => 5,
        ]);

        $this->assertFalse($producto->tieneAlertaStock());
    }
}
