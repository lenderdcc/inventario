<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockBajoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $producto;

    public function __construct($producto)
    {
        $this->producto = $producto;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // enviará por correo y la guardará en la BD
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Alerta de Stock Bajo')
            ->greeting('Hola, administrador')
            ->line("El producto **{$this->producto->nombre}** tiene un stock actual de **{$this->producto->stock_actual}**.")
            ->line("El stock mínimo de alerta es **{$this->producto->stock_minimo}**.")
            ->line('Por favor, revisa el inventario y repón las existencias si es necesario.')
            ->action('Ver producto', url("/productos/{$this->producto->id}"))
            ->line('Gracias por usar el sistema de gestión de inventario.');
    }

    public function toArray($notifiable)
    {
        return [
            'mensaje' => "El producto {$this->producto->nombre} tiene stock bajo ({$this->producto->stock_actual}).",
            'producto_id' => $this->producto->id,
        ];
    }
}
