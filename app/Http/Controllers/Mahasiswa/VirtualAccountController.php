<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\BNIApiService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

class VirtualAccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user instanceof User) {
            Log::error('🚨 Gagal mendapatkan user yang valid dari Auth.');
            return redirect()->route('login')->with('error', 'Sesi Anda tidak valid. Silakan login kembali.');
        }

            $vaExpired = $user->va_expired_at ? Carbon::parse($user->va_expired_at) : null;

            // Cek apakah perlu generate VA baru
            $needNewVA = empty($user->va_number) || !$vaExpired || now()->greaterThan($vaExpired);

            if ($needNewVA) {
                Log::info('🔄 VA diperlukan untuk user', [
                    'user_id' => $user->id,
                    'reason' => empty($user->va_number) ? 'belum ada' : 'expired'
                ]);

                $bni = new BNIApiService();
                $amount = config('app.bni_amount', 300000);//jangan di set di controller

                // Panggil service - retry sudah di-handle internal
                $response = $bni->createBilling($user, $amount);

                if ($response && $response['status'] && !empty($response['virtual_account'])) {
                    $vaNumber = $response['virtual_account'];
                    $expiredTime = Carbon::parse($response['datetime_expired']);

                    Log::info('✅ VA berhasil dibuat', [
                        'user_id'    => $user->id,
                        'va_number'  => $vaNumber,
                        'expires_at' => $expiredTime->toDateTimeString()
                    ]);

                    $user->update([
                        'va_number'     => $vaNumber,
                        'va_expired_at' => $expiredTime,
                    ]);

                    $user->refresh();
                } else {
                    Log::warning('⚠️ Gagal membuat VA', [
                        'user_id' => $user->id,
                        'response' => $response
                    ]);
                    
                    // Tentukan error message yang spesifik
                    $errorMessage = $this->getBNIErrorMessage();
                    
                    return view('mahasiswa.va.dashboard', [
                        'va' => null,
                        'error_message' => $errorMessage
                    ]);
                }
            }

            $va = (object) [
                'number'     => $user->va_number,
                'expires_at' => $user->va_expired_at,
            ];

    }

    /**
     * Generate error message berdasarkan environment
     */
    private function getBNIErrorMessage(): string
    {
        $isProduction = env('APP_ENV') === 'production';
        $apiUrl = env('BNI_API_URL', 'unknown');
        
        // Deteksi environment BNI dari URL
        $isProductionBNI = strpos($apiUrl, 'apibeta') === false;
        
        if ($isProductionBNI) {
            // Production BNI - Error 009 biasanya IP whitelist
            return "⚠️ Gagal membuat Virtual Account\n\n"
                . "PENYEBAB UTAMA (Production Environment):\n"
                . "• IP Server belum terdaftar di BNI Whitelist\n"
                . "• Endpoint: {$apiUrl}\n\n"
                . "LANGKAH PENYELESAIAN:\n"
                . "1. Hubungi administrator untuk cek IP server\n"
                . "2. Kontak BNI Support untuk whitelist IP\n"
                . "3. Tunggu konfirmasi dari BNI (biasanya 1-2 hari kerja)\n\n"
                . "Untuk testing, silakan gunakan environment sandbox terlebih dahulu.";
        } else {
            // Sandbox BNI
            return "⚠️ Gagal membuat Virtual Account\n\n"
                . "PENYEBAB (Sandbox Environment):\n"
                . "• Kredensial BNI mungkin tidak valid\n"
                . "• Library enkripsi tidak sesuai\n"
                . "• Koneksi ke BNI API terputus\n\n"
                . "Silakan hubungi administrator untuk pengecekan lebih lanjut.";
        }
    }
}