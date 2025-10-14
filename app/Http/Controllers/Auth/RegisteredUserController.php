<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BNIApiService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Exception;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request, BNIApiService $bniService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'regex:/^8[1-9][0-9]{7,10}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // 1️⃣ Buat user baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'mahasiswa_baru',
            ]);

            event(new Registered($user));

            // 2️⃣ Tentukan nominal biaya pendaftaran
            $amount = 300000;

            // 3️⃣ Panggil API BNI
            $response = $bniService->createBilling($user, $amount);

            // 4️⃣ Validasi respons API
            if (!empty($response) && isset($response['virtual_account'])) {
                $user->update([
                    'va_number' => $response['virtual_account'],
                    'va_expired_at' => $response['datetime_expired'] ?? now()->addDays(1),
                    'va_amount' => $amount,
                ]);

                Log::info('✅ VA berhasil dibuat untuk user ' . $user->id, $response);

                Auth::login($user);

                return redirect()->route('mahasiswa.va.dashboard')
                    ->with('success', 'Registrasi berhasil! Virtual Account Anda: ' . $response['virtual_account']);
            }

            // 5️⃣ Jika gagal membuat VA
            Log::error('❌ Gagal membuat VA untuk user ' . $user->id, ['response' => $response]);
            $user->delete();

            return back()->withInput()->with('error', 'Gagal membuat tagihan pembayaran. Silakan coba lagi.');

        } catch (Exception $e) {
            Log::error('💥 Exception saat registrasi', ['exception' => $e]);
            if (isset($user)) {
                $user->delete();
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan hubungi administrator.');
        }
    }
}