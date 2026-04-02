<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

  protected $fillable = [
    'nombre',
    'referencia',
    'stock_actual',
    'stock_minimo_alerta',
    'precio',
    'imagenes'
];
  public function tieneAlertaStock()
{
    return $this->stock_actual <= $this->stock_minimo_alerta;
}

}