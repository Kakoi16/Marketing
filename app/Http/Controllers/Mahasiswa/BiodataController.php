<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiodataController extends Controller
{
    /**
     * Menampilkan halaman formulir biodata (statis).
     */
    public function create()
    {
        return view('mahasiswa.biodata');
    }
}