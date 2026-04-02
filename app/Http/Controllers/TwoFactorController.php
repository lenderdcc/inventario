<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TwoFactorEnabledNotification;

class TwoFactorController extends Controller
{
    /**
     * Muestra el código QR para habilitar el 2FA
     */
    public function show()
    {
        $user = Auth::user();

        // Si el usuario no tiene un secreto generado, crearlo
        if (!$user->two_factor_secret) {
            $google2fa = app('pragmarx.google2fa');
            $user->two_factor_secret = encrypt($google2fa->generateSecretKey());
            $user->save();
        }

        // Generar el QR Code para escanear en Google Authenticator
        $google2fa = app('pragmarx.google2fa');
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            decrypt($user->two_factor_secret)
        );

        return view('profile.two-factor', compact('QR_Image'));
    }

    /**
     * Confirma el código ingresado por el usuario
     */
public function confirm(Request $request)
{
    $user = Auth::user();
    $google2fa = app('pragmarx.google2fa');

    // Validar el código ingresado
    $valid = $google2fa->verifyKey(
        decrypt($user->two_factor_secret),
        $request->input('code')
    );

    if ($valid) {
        // Activar el 2FA oficialmente
        $user->two_factor_enabled = true;
        $user->save();

        // ✅ Disparar notificación
        $user->notify(new TwoFactorEnabledNotification());

        return back()->with('success', 'Autenticación de doble factor activada correctamente.');
    }

    return back()->with('error', 'El código no es válido. Inténtalo de nuevo.');
}

    /**
     * Desactiva el 2FA si el usuario lo desea
     */
    public function disable()
    {
        $user = Auth::user();
        $user->two_factor_secret = null;
        $user->two_factor_enabled = false;
        $user->save();

        return back()->with('success', ' Autenticación de doble factor desactivada correctamente.');
    }
}
