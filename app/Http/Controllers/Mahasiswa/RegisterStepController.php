<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class RegisterStepController extends Controller
{
    public function index(Request $request)
    {
        // Setiap kali halaman direfresh, hapus hasil OCR sebelumnya
        Session::forget('ocr_results');
        $results = [];

        return view('mahasiswa.register-step', compact('results'));
    }

    // 🔹 Upload file KK & Ijazah
    public function uploadFiles(Request $request)
    {
        $request->validate([
            'file_kk' => 'required|file|mimes:pdf|max:5120',
            'file_ijazah' => 'required|file|mimes:pdf|max:5120',
        ]);

        $kk = $request->file('file_kk')->store('uploads/kk', 'public');
        $ijazah = $request->file('file_ijazah')->store('uploads/ijazah', 'public');

        return response()->json([
            'success' => true,
            'message' => 'Berkas berhasil diunggah.',
            'paths' => compact('kk', 'ijazah'),
        ]);
    }

    // 🔹 Validasi file dengan OCR
    public function validateFiles(Request $request)
    {
        try {
            $request->validate([
                'file_kk' => 'required|file|mimes:pdf|max:5120',
                'file_ijazah' => 'required|file|mimes:pdf|max:5120',
            ]);

            $ocrController = new \App\Http\Controllers\Pdf\OCRController();

            // Coba panggil OCR lokal (tanpa API eksternal)
            $jsonResponse = $ocrController->scan($request);
            $data = json_decode($jsonResponse->getContent(), true);
            $statusCode = $jsonResponse->getStatusCode();

            Log::info('Response OCR Lokal', ['status' => $statusCode, 'body' => $data]);

            // Jika gagal, tampilkan pesan yang lebih ramah
            if ($statusCode >= 400 || empty($data['success']) || $data['success'] === false) {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Pemindaian gagal. Pastikan file terbaca dan coba lagi.',
                ], $statusCode);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dipindai dan valid.',
                'results' => $data['results'] ?? [],
            ]);
        } catch (\Throwable $e) {
            Log::error('Validasi OCR gagal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 🔹 Simpan hasil koreksi ke JSON
    public function saveCorrections(Request $request)
    {
        try {
            $data = $request->except(['_token']);
            Storage::disk('public')->put('uploads/hasil_koreksi.json', json_encode($data, JSON_PRETTY_PRINT));

            return response()->json([
                'success' => true,
                'message' => 'Data koreksi berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan file koreksi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data di server.',
            ], 500);
        }
    }

    // === METHOD BARU DITAMBAHKAN DI SINI ===
    /**
     * Menampilkan halaman registrasi jalur promo.
     */
    public function showPromo()
    {
        // Menampilkan file view 'register-promo.blade.php'
        return view('mahasiswa.register-promo');
    }
}