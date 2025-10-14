<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Selesai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; /* slate-50 */
        }
    </style>
</head>
<body class="bg-slate-50 font-sans">
    
    <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-br from-blue-600 to-indigo-700 opacity-20 -z-10"></div>

    <div class="max-w-4xl mx-auto my-12 lg:my-24">
        <div class="text-center mb-8">
            <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-800">Satu Langkah Terakhir</h1>
            <p class="mt-2 text-gray-500">Proses pembuatan akun dan pendaftaran Anda hampir selesai.</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 lg:p-10">
        
            <!-- STEPPER / PROGRES -->
            <div>
                <div class="flex items-center">
                    <!-- Step 1: Registrasi Akun (Selesai) -->
                    <div class="flex items-center text-green-600 relative">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-green-600 text-white">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-green-600">Registrasi Selesai</div>
                    </div>

                    <!-- Garis Penghubung -->
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-600"></div>

                    <!-- Step 2: Login (Langkah Saat Ini) -->
                    <div class="flex items-center text-blue-600 relative">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center border-2 border-blue-600 animate-pulse">
                            <span class="text-lg font-bold">2</span>
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600">Login ke Dashboard</div>
                    </div>
                </div>
            </div>

            <hr class="my-8 border-dashed">

            <!-- KONTEN UTAMA -->
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Registrasi Berhasil!</h2>
                <p class="text-gray-600 max-w-xl mx-auto">Akun Anda telah berhasil dibuat. Langkah terakhir adalah login ke dashboard mahasiswa untuk melanjutkan proses pendaftaran.</p>
                <div class="mt-8">
                    <a href="{{ route('login') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition-transform transform hover:scale-105">
                        Lanjut ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
