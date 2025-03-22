<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = $request->email;
        $cacheKey = "login_attempts_{$email}";
        $blockKey = "login_blocked_{$email}";

        // Verificar si el usuario está bloqueado temporalmente
        if (Cache::has($blockKey)) {
            $remainingTime = Cache::get($blockKey) - now()->timestamp;
            return back()->withErrors(['email' => "Cuenta bloqueada por 3 minutos. Tiempo restante: {$remainingTime} segundos."]);
        }

        $user = User::where('email', $email)->first();

        // Si el usuario existe y está bloqueado permanentemente
        if ($user && $user->isBlocked()) {
            return back()->withErrors(['email' => 'Tu cuenta está bloqueada. Contacta al administrador.']);
        }

        // Intentar autenticar
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            Cache::forget($cacheKey); // Resetear intentos al autenticar correctamente

            $authenticatedUser = Auth::user();
            if ($authenticatedUser->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }

        // Incrementar intentos fallidos
        $attempts = Cache::get($cacheKey, 0) + 1;
        $remainingAttempts = 3 - $attempts;

        if ($attempts >= 3) {
            Cache::put($blockKey, now()->addMinutes(3)->timestamp, 180); // Bloquear por 3 minutos
            Cache::forget($cacheKey); // Resetear intentos
            return back()->withErrors(['email' => 'Cuenta bloqueada por 3 minutos debido a múltiples intentos fallidos.']);
        }

        Cache::put($cacheKey, $attempts, 300); // Guardar intentos por 5 minutos
        return back()->withErrors(['email' => "Credenciales incorrectas. Te quedan {$remainingAttempts} intentos."]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function checkUserStatus(Request $request)
    {
        $email = $request->query('email');
        $cacheKey = "login_attempts_{$email}";
        $blockKey = "login_blocked_{$email}";

        $user = User::where('email', $email)->first();
        $attempts = Cache::get($cacheKey, 0);
        $remainingAttempts = max(0, 3 - $attempts);

        if (Cache::has($blockKey)) {
            $remainingTime = Cache::get($blockKey) - now()->timestamp;
            return response()->json([
                'status' => 'blocked',
                'blocked' => true,
                'remaining_time' => $remainingTime,
                'message' => "Cuenta bloqueada por 3 minutos. Tiempo restante: {$remainingTime} segundos."
            ]);
        }

        if (!$user) {
            return response()->json(['status' => 'not_found', 'remaining_attempts' => $remainingAttempts]);
        }

        return response()->json([
            'status' => $user->status,
            'blocked' => $user->isBlocked(),
            'remaining_attempts' => $remainingAttempts
        ]);
    }
}