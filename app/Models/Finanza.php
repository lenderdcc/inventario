<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finanza extends Model
{
    protected $table = 'finanzas';

    protected $fillable = [
        'producto_id',
        'user_id',
        'metodo_pago',
        'estado_pago',
        'referencia_pago',
        'descripcion',
        'fecha_procesado',
    ];

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
