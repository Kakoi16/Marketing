<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\BNIApiService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VirtualAccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $bni = new BNIApiService();

        try {
            // Jika VA belum ada atau expired, generate baru
            if (empty($user->va_number) || !$user->va_expired_at || now()->greaterThan($user->va_expired_at)) {
                $data = (object) [
                    'va' => str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'nama' => $user->name,
                    'email' => $user->email,
                    'no_hp' => $user->phone,
                ];

                Log::info('Generate VA untuk user:', ['user_id' => $user->id, 'email' => $user->email]);

                $response = $bni->createVA($data, 300000);
                Log::info('Response dari BNI API:', ['response' => $response]);

                if ($response && isset($response['virtual_account'])) {
                    $expiredTime = $bni->datetimeExpired ?? now()->addMinutes(5);

                    $user->update([
                        'va_number' => $response['virtual_account'],
                        'va_expired_at' => Carbon::parse($expiredTime),
                    ]);

                    Log::info('VA berhasil disimpan ke user:', ['va_number' => $response['virtual_account']]);
                } else {
                    // Gunakan dummy jika gagal
                    $dummyVA = '988' . $bni->clientId . str_pad($user->id, 4, '0', STR_PAD_LEFT);
                    $expiredTime = now()->addMinutes(5);

                    $user->update([
                        'va_number' => $dummyVA,
                        'va_expired_at' => $expiredTime,
                    ]);

                    Log::warning('BNI API gagal, gunakan dummy VA:', ['va_number' => $dummyVA]);
                }
            }

            $va = (object) [
                'number' => $user->va_number,
                'expires_at' => $user->va_expired_at,
            ];

            Log::info('VA data untuk view:', ['va' => $va]);

            return view('mahasiswa.va.dashboard', compact('va'));
        } catch (\Throwable $e) {
            Log::error('Error di dashboard VA: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memuat data Virtual Account.');
        }
    }
}
