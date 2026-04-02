<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Producto;

class OperarioNoPuedeEditarStockTest extends DuskTestCase
{
    /** @test */
    public function operario_no_puede_editar_el_stock_minimo()
    {
        $operario = User::factory()->create()->assignRole('Operario');

        $producto = Producto::factory()->create([
            'stock_actual' => 10,
            'stock_minimo_alerta' => 5,
        ]);

        $this->browse(function (Browser $browser) use ($operario, $producto) {
            $browser->loginAs($operario)
                ->visit('/productos/' . $producto->id . '/edit')
                ->assertSee('Editar Producto')
                ->assertInputMissing('stock_minimo_alerta'); //  No debe existir el campo
        });
    }
}
