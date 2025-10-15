<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VirtualAccountController extends Controller
{
    /**
     * Menampilkan dashboard Virtual Account.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Cek jika data VA tidak lengkap setelah registrasi.
        if (empty($user->va_number) || empty($user->va_expired_at) || empty($user->va_amount)) {
            
            // ALIHKAN KE HALAMAN YANG SAMA, TAPI DENGAN PESAN ERROR
            // Ini akan menghentikan redirect loop.
            return view('mahasiswa.va.dashboard', [
                'user' => null, // Kirim null agar view tahu ada masalah
                'error_message' => 'Gagal membuat Virtual Account saat registrasi. Silakan hubungi administrator untuk bantuan.'
            ]);
        }

        // Jika data VA lengkap, tampilkan seperti biasa.
        return view('mahasiswa.va.dashboard', [
            'user' => $user
        ]);
    }
}