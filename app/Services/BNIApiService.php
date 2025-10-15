<?php

namespace App\Services;

use App\Libraries\Bnienc;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BNIApiService
{
    protected $clientId;
    protected $secretKey;
    protected $apiUrl;
    protected $vaPrefix;

    public function __construct()
    {
        $this->clientId = env('BNI_CLIENT_ID');
        $this->secretKey = env('BNI_SECRET_KEY');
        $this->vaPrefix = env('BNI_VA_PREFIX', ''); // Prefix VA jika ada

        // Pastikan URL diakhiri dengan /
        $this->apiUrl = rtrim(env('BNI_API_URL', 'https://api.bni-ecollection.com/'), '/') . '/';

        if (empty($this->clientId) || empty($this->secretKey)) {
            Log::critical('❌ BNI_CLIENT_ID atau BNI_SECRET_KEY belum diatur di file .env');
        }
    }

    public function createBilling($user, $amount, $retryCount = 0)
    {
        if (empty($this->clientId) || empty($this->secretKey)) {
            Log::error('❌ Kredensial BNI tidak lengkap');
            return null;
        }

        if ($retryCount >= 2) {
            Log::error('❌ Max retry tercapai untuk createBilling', ['user_id' => $user->id]);
            return null;
        }

        try {
            $trxId = 'PMB' . now()->format('YmdHis') . $user->id;

            // Validasi dan format phone
            $phone = preg_replace('/\D/', '', $user->phone);
            if (strlen($phone) < 10 || strlen($phone) > 13) {
                Log::warning('⚠️ Nomor phone invalid', ['phone' => $user->phone, 'user_id' => $user->id]);
                // Tetap lanjutkan dengan nomor apa adanya, BNI mungkin bisa handle
                $customerPhone = $phone;
            } else {
                if (substr($phone, 0, 2) === '62') {
                    $customerPhone = '0' . substr($phone, 2);
                } elseif (substr($phone, 0, 1) !== '0') {
                    $customerPhone = '0' . $phone;
                } else {
                    $customerPhone = $phone;
                }
            }

            // Sanitasi nama - maksimal 50 karakter
            $sanitizedName = preg_replace('/[^a-zA-Z0-9\s]/', '', trim($user->name));
            $sanitizedName = substr($sanitizedName, 0, 50);
            if (empty($sanitizedName)) {
                Log::warning('⚠️ Nama pelanggan kosong setelah sanitasi', ['original' => $user->name]);
                return null; // Nama wajib diisi
            }

            // Format datetime sesuai dokumentasi BNI (WIB)
            $datetimeExpired = now()->addDay()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');

            // Generate VA number (open VA jika prefix kosong)
            $vaPrefixUtama = '98841625';  // kode tetap
            $vaKodeUrut = '2600';         // ubah dari 2510 ke 2600
            $vaAkhir = str_pad($user->id, 4, '0', STR_PAD_LEFT); // urutan 0001, 0002, dst
            $virtualAccount = $vaPrefixUtama . $vaKodeUrut . $vaAkhir;

            // =================================================================
            // PERUBAHAN UTAMA: Menggunakan data dinamis, bukan hardcode
            // =================================================================
            $payload = [
                'type'             => 'createBilling',
                'client_id'        => $this->clientId,
                'trx_id'           => $trxId,
                'trx_amount'       => $amount,
                'billing_type'     => 'c', // c: create, u: update, d: delete
                'datetime_expired' => $datetimeExpired,
                'virtual_account'  => $virtualAccount,
                'customer_name'    => $sanitizedName,
                'customer_email'   => $user->email,
                'customer_phone'   => $customerPhone,
            ];

            $encryptedPayload = Bnienc::encrypt($payload, $this->clientId, $this->secretKey);

            $requestBody = [
                'client_id' => $this->clientId,
                'data'      => $encryptedPayload,
            ];

            Log::info('📤 Mengirim request ke BNI', ['payload' => $payload]);

            // =================================================================
            // DIRAPIKAN: Menggunakan Laravel HTTP Client secara konsisten
            // =================================================================
            $response = Http::timeout(45)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post($this->apiUrl, $requestBody); // Kirim array, Laravel otomatis encode ke JSON

            $statusCode = $response->status();
            $rawBody = $response->body();

            Log::info('📥 Respons mentah BNI', [
                'status_code' => $statusCode,
                'body'        => $rawBody,
            ]);

            if (!$response->successful()) { // Cek status 2xx
                Log::error('❌ HTTP Error dari BNI', ['status' => $statusCode, 'body' => $rawBody]);
                return null;
            }

            $result = $response->json(); // Decode JSON

            if (empty($result) || !is_array($result)) {
                Log::error('❌ Respons BNI bukan JSON valid', ['raw' => $rawBody]);
                return null;
            }

            // Cek status dari BNI
            $status = $result['status'] ?? 'unknown';
            if ($status !== '000') {
                // Log error spesifik dari BNI
                Log::error('❌ Gagal membuat VA di BNI', [
                    'status'   => $status,
                    'message'  => $result['message'] ?? 'no message',
                    'response' => $result,
                    'trx_id'   => $trxId,
                ]);
                return null; // Langsung hentikan jika gagal
            }

            // Decrypt response data jika sukses
            $decrypted = !empty($result['data'])
                ? Bnienc::decrypt($result['data'], $this->clientId, $this->secretKey)
                : null;

            Log::info('🔓 Hasil dekripsi BNI', ['decrypted_data' => $decrypted]);

            $vaNumber = $decrypted['virtual_account'] ?? null;

            if (empty($vaNumber)) {
                Log::warning('⚠️ VA tidak ditemukan dalam response BNI', [
                    'user_id' => $user->id,
                    'decrypted' => $decrypted
                ]);
                return null;
            }

            Log::info('✅ VA berhasil dibuat', [
                'user_id' => $user->id,
                'va_number' => $vaNumber
            ]);

            return [
                'status'           => true,
                'virtual_account'  => $vaNumber,
                'trx_id'           => $decrypted['trx_id'] ?? $trxId,
                'datetime_expired' => $datetimeExpired,
            ];
        } catch (Exception $e) {
            Log::error('💥 Exception saat createBilling', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);
            return null;
        }
    }

    public function inquiryBilling($trxId)
    {
        // ... (method inquiryBilling Anda sudah cukup baik, cukup hapus dd() jika ada)
        try {
            $payload = [
                'type' => 'inquirybilling',
                'client_id' => $this->clientId,
                'trx_id' => $trxId,
            ];

            $encryptedPayload = Bnienc::encrypt($payload, $this->clientId, $this->secretKey);

            $requestBody = [
                'client_id' => $this->clientId,
                'data' => $encryptedPayload,
            ];

            $response = Http::timeout(30)->post($this->apiUrl, $requestBody);

            if (!$response->successful()) {
                Log::error('Inquiry Gagal (HTTP Error)', ['status' => $response->status(), 'trx_id' => $trxId]);
                return null;
            }

            $result = $response->json();

            if (($result['status'] ?? '') !== '000') {
                Log::warning('Inquiry Gagal (BNI Status)', ['response' => $result, 'trx_id' => $trxId]);
                return null;
            }

            return Bnienc::decrypt($result['data'], $this->clientId, $this->secretKey);
        } catch (Exception $e) {
            Log::error('💥 Exception di inquiryBilling', [
                'message' => $e->getMessage(),
                'trx_id' => $trxId
            ]);
            return null;
        }
    }
}
