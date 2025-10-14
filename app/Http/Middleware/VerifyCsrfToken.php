<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'ocr/scan',                 // ✅ abaikan token CSRF untuk route OCR
        'mahasiswa/validate',       // (opsional) jika validateFiles juga kirim file langsung
    ];
}
