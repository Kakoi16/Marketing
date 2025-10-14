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
     * Handle registration request safely.
     */
    public function store(Request $request, BNIApiService $bniService): RedirectResponse
    {
        set_time_limit(60);

        Log::info('📥 Menerima request register', $request->all());

        //try {
            // PERUBAHAN 1: Menambahkan validasi regex untuk format 08...
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
                // 'unique:users,email'
            ],
                'phone' => [
                    'required',
                    // 'unique:users,phone',
                    'regex:/^08[0-9]{8,11}$/' // Memastikan format nomor adalah 08... (total 10-13 digit)
                ],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            Log::info('✅ Validasi sukses', $validated);

            // PERUBAHAN 2: Normalisasi nomor telepon dari "08" menjadi "628"
            $normalizedPhone = $validated['phone'];
            if (str_starts_with($normalizedPhone, '08')) {
                $normalizedPhone = '62' . substr($normalizedPhone, 1);
            }

            // Simpan user baru dengan nomor yang sudah dinormalisasi
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $normalizedPhone, // Menggunakan nomor yang sudah diubah ke 62
                'password' => Hash::make($validated['password']),
                'role' => 'mahasiswa_baru',
            ]);

            Log::info('✅ User berhasil dibuat', ['id' => $user->id, 'phone' => $normalizedPhone]);
            
            // Event & login user
            event(new Registered($user));
            Auth::login($user);

            // Jumlah tagihan
            $amount = 300000;
            Log::info('💰 Membuat VA', ['user_id' => $user->id, 'amount' => $amount]);

            //try {
                // Proses ke BNI
                $response = $bniService->createBilling($user, $amount);
                // dd($response);
                Log::info('📦 Respons API BNI', ['response' => $response]);

                // Jika sukses buat VA
                if (isset($response['virtual_account']) && !empty($response['virtual_account'])) {
                    $user->update([
                        'va_number' => $response['virtual_account'],
                        'va_expired_at' => $response['datetime_expired'] ?? now()->addDays(1),
                        'va_amount' => $amount,
                    ]);

                    Log::info("✅ VA berhasil dibuat untuk user {$user->id}");
                } else {
                    // Simpan pesan error jika gagal
                    Log::warning('⚠️ Respons VA kosong / tidak valid', ['response' => $response]);
                    $user->update(['va_status' => 'FAILED']);
                }
            // } catch (Exception $e) {
            //     Log::error('❌ Error saat membuat VA', ['message' => $e->getMessage()]);
            //     $user->update(['va_status' => 'ERROR']);
            // }

            // Redirect ke dashboard
            return redirect()
                ->route('mahasiswa.va.dashboard')
                ->with('success', 'Registrasi berhasil! Silakan lanjut ke dashboard untuk melihat Virtual Account Anda.');

        // } catch (Exception $e) {
        //     Log::error('💥 Exception saat registrasi', [
        //         'error' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString(),
        //     ]);

        //     return back()
        //         ->withInput()
        //         ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        // }
    }
}