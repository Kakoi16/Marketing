<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Libraries\Bnienc; // Pastikan path library ini benar
use Illuminate\Http\Client\ConnectionException;

class BNIApiService
{
    protected $client_id;
    protected $secret_key;
    protected $url;
    public $datetime_expired;
    protected $bnienc;

    public function __construct()
    {
        // Ambil konfigurasi dari config/services.php
        $this->client_id = config('services.bni.client_id');
        $this->secret_key = config('services.bni.secret_key');
        $this->url = config('services.bni.url');
        
        $this->datetime_expired = date('Y-m-d H:i:s', strtotime('+24 hours')); // Contoh: expired dalam 24 jam

        // Inisialisasi Bnienc
        $this->bnienc = new Bnienc();
    }

    public function createBilling(User $user, int $amount): ?array
    {
        // Pastikan client_id dan secret_key tidak kosong
        if (empty($this->client_id) || empty($this->secret_key)) {
            Log::error('❌ Konfigurasi BNI (Client ID / Secret Key) tidak ditemukan.');
            return null;
        }

        // Generate virtual account unik
        $vaNumber = '988' . $this->client_id . str_pad($user->id, 6, '0', STR_PAD_LEFT);

        // trx_id unik
        $trxId = 'PMB-' . $user->id . '-' . substr(md5(uniqid(rand(), true)), 0, 5);

        $payloadData = [
            'type'             => 'createBilling',
            'client_id'        => $this->client_id,
            'trx_id'           => $trxId,
            'trx_amount'       => $amount,
            'billing_type'     => 'c',
            'customer_name'    => preg_replace('/[^a-zA-Z0-9\s]/', '', $user->name), // Hapus karakter non-alfanumerik
            'customer_email'   => $user->email,
            'customer_phone'   => $user->phone,
            'virtual_account'  => $vaNumber,
            'datetime_expired' => $this->datetime_expired,
        ];

        Log::info('📤 Mengirim request ke BNI', ['url' => $this->url, 'payload' => $payloadData]);

        // Encrypt payload
        $hashedString = $this->bnienc->encrypt($payloadData, $this->client_id, $this->secret_key);
        $requestPayload = [
            'client_id' => $this->client_id,
            'data' => $hashedString,
        ];

        try {
            $response = Http::timeout(15)->post($this->url, $requestPayload);
        } catch (ConnectionException $e) {
            Log::error('❌ Gagal koneksi ke BNI', ['exception' => $e->getMessage()]);
            return null;
        } catch (\Exception $e) {
            Log::error('❌ Gagal request ke BNI', ['exception' => $e->getMessage()]);
            return null;
        }

        $responseJson = $response->json();
        Log::info('📥 Respons dari BNI', $responseJson);

        if (!isset($responseJson['status']) || $responseJson['status'] !== '000') {
            Log::error('❌ Status dari BNI bukan 000', $responseJson);
            return null;
        }

        $decryptedData = $this->bnienc->decrypt($responseJson['data'], $this->client_id, $this->secret_key);

        if (!$decryptedData) {
            Log::error('❌ Gagal dekripsi data dari BNI.');
            return null;
        }

        // Tambahkan info VA kembali untuk disimpan di database
        $decryptedData['virtual_account'] = $vaNumber;
        $decryptedData['trx_amount'] = $amount;
        $decryptedData['datetime_expired'] = $this->datetime_expired;

        return $decryptedData;
    }
}