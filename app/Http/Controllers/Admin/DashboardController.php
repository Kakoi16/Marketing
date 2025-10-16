<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $userCount = User::count();

        // 🔹 Ambil semua tahun yang ada di tabel users untuk role mahasiswa_baru
        $availableYears = User::where('role', 'mahasiswa_baru')
            ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->toArray();

        // Jika tidak ada data sama sekali
        if (empty($availableYears)) {
            return view('admin.dashboard', [
                'user' => $user,
                'userCount' => $userCount,
                'userPercentage' => 0,
                'usersThisYear' => 0,
                'targetUsers' => 10000,
                'chartData' => [],
                'availableYears' => [],
            ]);
        }

        // 🔹 Ambil tahun terbaru sebagai "tahun utama" untuk menampilkan di dashboard
        $currentYear = max($availableYears);

        // 🔹 Hitung jumlah pendaftar tahun ini
        $usersThisYear = User::where('role', 'mahasiswa_baru')
            ->whereYear('created_at', $currentYear)
            ->count();

        // 🔹 Target total pendaftar
        $targetUsers = 10000;
        $userPercentage = $targetUsers > 0 ? ($usersThisYear / $targetUsers) * 100 : 0;

        // 🔹 Siapkan data perbandingan untuk semua tahun yang tersedia
        $chartData = [];
        foreach ($availableYears as $year) {
            $monthlyCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = User::where('role', 'mahasiswa_baru')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
                $monthlyCounts[] = $count;
            }

            $chartData[] = [
                'year' => $year,
                'data' => $monthlyCounts,
            ];
        }

        // 🔹 Kirim semua data ke view
        return view('admin.dashboard', compact(
            'user',
            'userCount',
            'userPercentage',
            'usersThisYear',
            'targetUsers',
            'chartData',
            'availableYears',
            'currentYear'
        ));
        
    }
    public function chartData()
{
    // Ambil semua tahun yang tersedia
    $availableYears = User::where('role', 'mahasiswa_baru')
        ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
        ->orderBy('year', 'asc')
        ->pluck('year')
        ->toArray();

    if (empty($availableYears)) {
        return response()->json([
            'chartData' => [],
            'currentYear' => now()->year,
        ]);
    }

    $currentYear = max($availableYears);

    $chartData = [];
    foreach ($availableYears as $year) {
        $monthlyCounts = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = User::where('role', 'mahasiswa_baru')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $monthlyCounts[] = $count;
        }

        $chartData[] = [
            'year' => $year,
            'data' => $monthlyCounts,
        ];
    }

    return response()->json([
        'chartData' => $chartData,
        'currentYear' => $currentYear,
    ]);
}

}
