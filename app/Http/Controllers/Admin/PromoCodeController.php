<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
public function index()
{
    $promos = PromoCode::latest()->get();
    return view('admin.promocode.index', compact('promos'));
}

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promo_codes,code',
            'discount' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        PromoCode::create([
            'code' => $request->code,
            'discount' => $request->discount,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.promocode.index')->with('success', 'Kode promo berhasil dibuat!');
    }

    public function destroy($id)
    {
        $code = PromoCode::findOrFail($id);
        $code->delete();

        return redirect()->route('admin.promocode.index')->with('success', 'Kode promo dihapus.');
    }
}
