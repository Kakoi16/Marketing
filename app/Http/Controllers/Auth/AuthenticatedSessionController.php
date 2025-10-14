<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
      public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user(); // Cara lain untuk mendapatkan user yang login

        // Gunakan switch statement untuk logika yang lebih bersih
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
                break;
            
            case 'mahasiswa_baru':
                return redirect()->route('mahasiswa.dashboard');
                break;

            case 'user':
                return redirect()->route('dashboard');
                break;

            default:
                // Jika role tidak cocok sama sekali, logout-kan user
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Role Anda tidak diizinkan atau tidak dikenal.',
                ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}