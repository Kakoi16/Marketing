<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pendaftaran</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js (Diperlukan untuk animasi bertingkat) --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .hero-gradient { background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%); }

        /* Animasi Transisi Halaman (Sudah Ada) */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        .fade-in { animation: fadeIn 0.4s ease-out forwards; }
        .fade-out { animation: fadeOut 0.4s ease-out forwards; }

        /* === ANIMASI BARU DITAMBAHKAN DI SINI === */

        /* 1. Animasi Mengambang (untuk logo) */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* 2. Animasi Muncul dari Bawah (untuk kartu) */
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slideInUp {
            /* Diterapkan via JavaScript untuk efek stagger */
            animation: slideInUp 0.6s ease-out forwards;
        }
    </style>
</head>
<body class="bg-slate-50">

    <div class="min-h-screen flex flex-col">
        
        <header class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-8">
                        {{-- Menambahkan animasi mengambang pada logo --}}
                        <a href="/" class="animate-float">
                            <img class="h-10 w-auto" src="{{ asset('images/Logo-UBP-Karawang-1.png') }}" alt="Logo UBP">
                        </a>
                        <nav class="hidden md:flex space-x-6">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="page-link text-gray-900 font-semibold border-b-2 border-blue-500 px-1">Dashboard</a>
                            <a href="{{ route('mahasiswa.biodata.create') }}" class="page-link text-gray-500 hover:text-gray-900 font-medium">Biodata</a>
                        </nav>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Logout</button>
                        </form>
                    </div>
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div id="mobile-menu" class="hidden md:hidden">
                {{-- Konten menu mobile --}}
            </div>
        </header>

        <main class="flex-1 fade-in" x-data>
            <div class="hero-gradient text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-blue-100">Anda telah berhasil login. Mari mulai langkah pertama pendaftaran Anda.</p>
                </div>
            </div>
            
            {{-- Menambahkan x-init untuk trigger animasi --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10" x-init="$nextTick(() => {
                const elements = $el.querySelectorAll('.card-animate');
                elements.forEach((el, index) => {
                    el.style.animationDelay = `${index * 150}ms`;
                    el.classList.add('animate-slideInUp');
                });
            })">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Menambahkan kelas .card-animate dan opacity-0 untuk animasi --}}
                    <div class="lg:col-span-2 space-y-8 card-animate opacity-0">
                        {{-- Menambahkan efek hover pada kartu --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl">
                            <div class="flex items-center space-x-3 border-b pb-4">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <h3 class="text-lg font-bold text-gray-800">Progres Pendaftaran Anda</h3>
                            </div>
                            <div class="mt-6">
                                <ul class="space-y-4">
                                    <li class="flex items-center">
                                        <div class="flex-shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="ml-3 text-sm font-medium text-gray-500 line-through">Login & Verifikasi Akun</p>
                                    </li>
                                    <li class="flex items-center">
                                        <div class="flex-shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white animate-pulse">
                                            <span class="text-xs font-bold">2</span>
                                        </div>
                                        <p class="ml-3 text-sm font-semibold text-blue-600">Mulai Registrasi Ulang</p>
                                    </li>
                                    <li class="flex items-center">
                                        <div class="flex-shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-gray-300 text-gray-600">
                                            <span class="text-xs font-bold">3</span>
                                        </div>
                                        <p class="ml-3 text-sm font-medium text-gray-400">Selesaikan Pendaftaran</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-8 border-t pt-6">
                                <p class="text-gray-600 mb-4">Anda siap untuk melanjutkan? Klik tombol di bawah ini untuk memulai proses pengisian data dan upload berkas.</p>
                                <a href="{{ route('mahasiswa.register.step') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
                                    Mulai Registrasi Ulang
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Menambahkan kelas .card-animate dan opacity-0 untuk animasi --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 card-animate opacity-0">
                        <div class="flex items-center space-x-3 border-b pb-4">
                             <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Penting</h3>
                        </div>
                        <div class="mt-4 text-sm text-gray-600 space-y-2">
                            <p>Pastikan data yang Anda masukkan adalah benar dan sesuai dengan dokumen asli.</p>
                            <p>Batas akhir pendaftaran adalah <strong>30 Oktober 2025</strong>.</p>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        // ... (script mobile menu dan transisi halaman Anda tetap sama) ...
    </script>

</body>
</html>

