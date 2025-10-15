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

    public function store(Request $request, BNIApiService $bniService): RedirectResponse
    {
        set_time_limit(60);
        Log::info('📥 Menerima request register', $request->all());

        // Validasi dasar (tanpa unique)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^08[0-9]{8,11}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 🔎 Cek apakah email atau nomor HP sudah terdaftar
        $exists = User::where('email', $validated['email'])
            ->orWhere('phone', '62' . substr($validated['phone'], 1))
            ->exists();

        if ($exists) {
            Log::warning('⚠️ Email atau nomor HP sudah digunakan', [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau nomor telepon sudah terdaftar. Silakan gunakan yang lain.');
        }

        // Normalisasi nomor ke format 62
        $normalizedPhone = $validated['phone'];
        if (str_starts_with($normalizedPhone, '08')) {
            $normalizedPhone = '62' . substr($normalizedPhone, 1);
        }

        // 🔐 Simpan user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $normalizedPhone,
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa_baru',
        ]);

        Log::info('✅ User berhasil dibuat', ['id' => $user->id]);

        event(new Registered($user));
        Auth::login($user);

        // 🔧 Buat VA
        $amount = 300000;
        Log::info('💰 Membuat VA', ['user_id' => $user->id, 'amount' => $amount]);

        $response = $bniService->createBilling($user, $amount);
        Log::info('📦 Respons API BNI', ['response' => $response]);

        if (isset($response['virtual_account']) && !empty($response['virtual_account'])) {
            $user->update([
                'va_number' => $response['virtual_account'],
                'va_expired_at' => $response['datetime_expired'] ?? now()->addDays(1),
                'va_amount' => $amount,
            ]);
        } else {
            Log::warning('⚠️ Gagal membuat VA', ['response' => $response]);
            $user->update(['va_status' => 'FAILED']);
        }

        return redirect()
            ->route('mahasiswa.va.dashboard')
            ->with('success', 'Registrasi berhasil! Silakan lanjut ke dashboard untuk melihat Virtual Account Anda.');
    }
}
