<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // <-- 1. Tambahkan ini

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index(): View // <-- 2. Tambahkan return type 'View'
    {
        $user = Auth::user();
        return view('admin.dashboard', compact('user')); 
    }
}