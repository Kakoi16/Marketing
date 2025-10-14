<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/driver.js/0.9.8/driver.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/driver.js/0.9.8/driver.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden; /* Mencegah scrollbar saat animasi */
        }
        .aurora-background {
            background-image: linear-gradient(rgba(26, 35, 126, 0.6), rgba(40, 53, 147, 0.6)), url('https://i.ibb.co/HT2MC98q/bp1.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        .driver-popover {
            max-width: 90vw !important;
            font-size: 14px !important;
        }
        .driver-popover .driver-popover-title {
            font-size: 16px !important;
        }

        /* === ANIMASI BARU DITAMBAHKAN DI SINI === */
        @keyframes slideInFromLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInFromRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .animate-slide-in-left {
            animation: slideInFromLeft 0.9s ease-out forwards;
        }
        .animate-slide-in-right {
            animation: slideInFromRight 0.9s ease-out forwards;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-900">
    <div class="fixed top-0 left-0 w-full h-full -z-10">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/UBP Karawang.png') }}');"></div>
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>

    <div class="flex items-center justify-center min-h-screen p-4 overflow-hidden">
        {{-- Ini adalah "Bingkai" Kartu --}}
        <div class="relative flex flex-col w-full max-w-4xl bg-white shadow-2xl rounded-2xl md:flex-row">
            
            {{-- Bagian Kiri (Informasi Universitas) - Diberi animasi --}}
            <div class="relative flex flex-col justify-between p-8 text-white rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none md:w-1/2 aurora-background animate-slide-in-left">
                <div class="z-10">
                    <img src="{{ asset('images/Logo-UBP-Karawang-1.png') }}" alt="Logo UBP Karawang" class="w-20 h-20 mb-4">
                    <h1 class="mb-3 text-3xl font-bold">Universitas Buana Perjuangan Karawang</h1>
                    <p class="text-base font-light leading-snug tracking-wide">
                        Raihlah masa depanmu yang cerah, indah, dan berwarna. Bersama Kami!
                    </p>
                </div>
                <div class="flex items-center justify-around mt-12 z-10 opacity-80 space-x-6">
                    <a href="https://instagram.com/ubpkarawangofficial" target="_blank" class="transition-transform transform hover:scale-110 hover:text-pink-500 hover:drop-shadow-[0_0_10px_rgba(236,72,153,0.8)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="https://facebook.com/ubpkarawangofficial" target="_blank" class="transition-transform transform hover:scale-110 hover:text-blue-600 hover:drop-shadow-[0_0_10px_rgba(37,99,235,0.8)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.13 8.44 9.88v-6.99H8.1v-2.89h2.34V9.41c0-2.32 1.38-3.61 3.49-3.61.99 0 2.02.18 2.02.18v2.22h-1.14c-1.13 0-1.48.7-1.48 1.42v1.71h2.52l-.4 2.89h-2.12v6.99C18.34 21.13 22 17 22 12z"/></svg>
                    </a>
                    <a href="https://youtube.com/@ubpkarawangofficial" target="_blank" class="transition-transform transform hover:scale-110 hover:text-red-500 hover:drop-shadow-[0_0_10px_rgba(239,68,68,0.8)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 11.75a29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    </a>
                </div>
            </div>

            {{-- Bagian Kanan (Konten Dinamis dari Login/Register) - Diberi animasi --}}
            <div class="flex flex-col justify-center p-8 md:p-10 md:w-1/2 animate-slide-in-right">
                {{-- Di sinilah konten dari file login.blade.php atau register.blade.php akan dimasukkan --}}
                {{ $slot }}
            </div>
            
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>