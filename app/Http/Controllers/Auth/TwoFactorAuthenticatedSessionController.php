<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use App\Models\User;

class TwoFactorAuthenticatedSessionController extends Controller
{
    // Mostrar formulario 2FA
    public function create()
    {
        return view('auth.two-factor-challenge');
    }

    // Verificar código TOTP
    public function store(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $userId = session('two_factor_pending');

        if (! $userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Usuario no encontrado o sesión expirada.'
            ]);
        }

        $user = User::find($userId);

        $provider = app(TwoFactorAuthenticationProvider::class);

        if (! $provider->verify(decrypt($user->two_factor_secret), $request->code)) {
            return back()->withErrors(['code' => 'Código incorrecto.']);
        }

        // Login completo
        Auth::login($user);
        session()->forget('two_factor_pending');

        return redirect()->intended('/dashboard');
    }
}
