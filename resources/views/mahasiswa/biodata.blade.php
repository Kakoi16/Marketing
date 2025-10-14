<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Biodata - PMB Online</title>
    
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

        /* === ANIMASI TRANSISI HALAMAN === */
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
                            <a href="{{ route('mahasiswa.dashboard') }}" class="page-link text-gray-500 hover:text-gray-900 font-medium">Dashboard</a>
                            <a href="{{ route('mahasiswa.biodata.create') }}" class="page-link text-gray-900 font-semibold border-b-2 border-blue-500 px-1">Biodata</a>
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
            <!-- Hero Section -->
            <div class="hero-gradient text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight">Formulir Biodata Pendaftaran</h1>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-blue-100">Pastikan data yang Anda masukkan sudah benar dan sesuai dengan dokumen asli.</p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
                {{-- Menambahkan x-init untuk trigger animasi --}}
                <form action="#" method="POST" class="space-y-8" x-init="$nextTick(() => {
                    const elements = $el.querySelectorAll('.card-animate');
                    elements.forEach((el, index) => {
                        el.style.animationDelay = `${index * 150}ms`;
                        el.classList.add('animate-slideInUp');
                    });
                })">
                    
                    <!-- BAGIAN 1: DATA DIRI -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200 card-animate opacity-0 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl">
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-4 mb-6">Data Diri</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap (sesuai Ijazah)</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                                <input type="text" name="nik" id="nik" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    <option>Laki-laki</option>
                                    <option>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="agama" class="block text-sm font-medium text-gray-700">Agama</label>
                                <input type="text" name="agama" id="agama" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: DATA KARTU KELUARGA -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200 card-animate opacity-0 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl">
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-4 mb-6">Data Kartu Keluarga (KK)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                <label for="no_kk" class="block text-sm font-medium text-gray-700">Nomor Kartu Keluarga</label>
                                <input type="text" name="no_kk" id="no_kk" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="nama_kepala_keluarga" class="block text-sm font-medium text-gray-700">Nama Kepala Keluarga</label>
                                <input type="text" name="nama_kepala_keluarga" id="nama_kepala_keluarga" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat_kk" class="block text-sm font-medium text-gray-700">Alamat (sesuai KK)</label>
                                <textarea name="alamat_kk" id="alamat_kk" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 3: DATA IJAZAH -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200 card-animate opacity-0 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl">
                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-4 mb-6">Data Pendidikan Terakhir (Ijazah)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700">NISN</label>
                                <input type="text" name="nisn" id="nisn" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="no_ijazah" class="block text-sm font-medium text-gray-700">Nomor Seri Ijazah</label>
                                <input type="text" name="no_ijazah" id="no_ijazah" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="asal_sekolah" class="block text-sm font-medium text-gray-700">Asal Sekolah</label>
                                <input type="text" name="asal_sekolah" id="asal_sekolah" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="jurusan_sekolah" class="block text-sm font-medium text-gray-700">Jurusan</Slabel>
                                <input type="text" name="jurusan_sekolah" id="jurusan_sekolah" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="tahun_lulus" class="block text-sm font-medium text-gray-700">Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" id="tahun_lulus" placeholder="YYYY" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan di Bagian Bawah -->
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 text-right card-animate opacity-0">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105">Simpan Semua Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Script untuk menu mobile
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Script untuk transisi halus
        document.addEventListener('DOMContentLoaded', function() {
            const pageLinks = document.querySelectorAll('.page-link');
            pageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== window.location.href) {
                        e.preventDefault();
                        document.body.classList.add('fade-out');
                        setTimeout(() => {
                            window.location.href = href;
                        }, 400); 
                    }
                });
            });
        });
    </script>
</body>
</html>

