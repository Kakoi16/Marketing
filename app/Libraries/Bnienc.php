<?php

namespace App\Libraries;

class Bnienc
{
    public static function encrypt($data, $clientId, $secretKey)
    {
        // Implementasi logika enkripsi BNI di sini
        // Ini adalah contoh sederhana, ganti dengan implementasi sebenarnya
        return base64_encode(json_encode($data) . $secretKey);
    }

    public static function decrypt($hashedData, $clientId, $secretKey)
    {
        // Implementasi logika dekripsi BNI di sini
        // Ini adalah contoh sederhana, ganti dengan implementasi sebenarnya
        $decoded = base64_decode($hashedData);
        $jsonString = str_replace($secretKey, '', $decoded);
        return json_decode($jsonString, true);
    }
}
