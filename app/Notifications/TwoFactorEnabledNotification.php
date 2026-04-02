<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TwoFactorEnabledNotification extends Notification
{
    use Queueable;

    /**
     * Definir los canales de notificación.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Email y DB
    }

    /**
     * Notificación por email
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('2FA Activado')
                    ->line('Has activado correctamente la autenticación de dos factores en tu cuenta.')
                    ->line('Si no realizaste esta acción, contacta al administrador inmediatamente.');
    }

    /**
     * Notificación para base de datos
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => '2FA Activado',
            'message' => 'Has habilitado la autenticación de dos factores en tu cuenta.',
            'action_url' => url('/profile'), // Puedes llevar al perfil o dashboard
        ];
    }
}
