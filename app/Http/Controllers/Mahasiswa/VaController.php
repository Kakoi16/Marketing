<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\BniEcollectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaController extends Controller
{
    protected $bni;

    public function __construct(BniEcollectionService $bni)
    {
        $this->bni = $bni;
    }

    /**
     * Tampilkan halaman dashboard mahasiswa
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('mahasiswa.va.dashboard', compact('user'));
    }

    /**
     * Buat Virtual Account BNI
     */
    public function createVA(Request $request)
    {
        $user = Auth::user();

        // jumlah tagihan bisa dari form atau default
        $amount = $request->input('amount', 300000);

        $result = $this->bni->createBilling($user, $amount);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Simpan VA ke database (opsional)
        $user->update([
            'virtual_account' => $result['va_data']['virtual_account'] ?? null,
        ]);

        return back()->with('success', 'Virtual Account berhasil dibuat: ' . ($result['va_data']['virtual_account'] ?? ''));
    }
}
