<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount',
        'expired_at',
    ];

    // Generate kode promo acak 6 digit (huruf & angka)
    public static function generateCode()
    {
        return strtoupper(Str::random(6));
    }
}
