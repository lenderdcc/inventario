<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Producto;

class SupervisorEditStockTest extends DuskTestCase
{
    /** @test */
    public function supervisor_puede_editar_el_stock_minimo()
    {
        $supervisor = User::factory()->create()->assignRole('Supervisor');

        $producto = Producto::factory()->create([
            'stock_actual' => 10,
            'stock_minimo_alerta' => 5,
        ]);

        $this->browse(function (Browser $browser) use ($supervisor, $producto) {
            $browser->loginAs($supervisor)
                ->visit('/productos/' . $producto->id . '/edit')
                ->assertSee('Editar Producto')
                ->type('stock_minimo_alerta', 3)
                ->press('Actualizar')
                ->assertPathIs('/dashboard')
                ->assertSee('Producto actualizado exitosamente.');
        });
    }
}
