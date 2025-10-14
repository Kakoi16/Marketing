<!DOCTYPE html>
<html lang="id" x-data="registerForm()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mahasiswa (Promo)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .progress-bar { transition: width 0.5s ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 font-sans">
    
    <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-br from-blue-600 to-indigo-700 opacity-20 -z-10"></div>

    <div class="max-w-4xl mx-auto my-12 lg:my-24">
        <div class="text-center mb-8">
            <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-800">Pendaftaran Jalur Promo</h1>
            <p class="mt-2 text-gray-500">Alur pendaftaran khusus untuk pengguna dengan kode promo.</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 lg:p-10">
        
            <!-- STEPPER / NAVIGASI LANGKAH -->
            <div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div class="bg-blue-600 h-2 rounded-full progress-bar" :style="`width: ${(step - 1) / 4 * 100}%`"></div>
                </div>
                <div class="flex justify-between">
                    {{-- Alur pendaftaran tanpa "Virtual Account" --}}
                    <template x-for="(label, index) in ['Data Diri', 'Pilih Jurusan', 'Upload PDF', 'Konfirmasi Data', 'Selesai']" :key="index">
                        <div class="w-1/5">
                            <div class="relative mb-2">
                                <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center" 
                                     :class="{
                                         'bg-blue-600 text-white shadow-lg': step >= index + 1,
                                         'bg-white border-2 border-gray-300 text-gray-500': step < index + 1
                                     }">
                                    <span class="text-center w-full" x-show="step <= index + 1" x-text="index + 1"></span>
                                    <svg x-show="step > index + 1" class="w-full h-full text-white p-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-center text-xs" :class="step >= index + 1 ? 'font-semibold text-blue-600' : 'text-gray-500'" x-text="label"></div>
                        </div>
                    </template>
                </div>
            </div>

            <hr class="my-8 border-dashed">

            <!-- KONTEN STEP -->
            <div>
                <!-- STEP 1: DATA DIRI -->
                <div x-show="step === 1" x-transition.opacity>
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Langkah 1: Isi Data Diri & Kode Promo</h2>
                    <form class="space-y-5">
                        {{-- Input Nama, WA, Gmail --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                            <input type="text" x-model="nama" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan nama lengkap anda">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Nomor Aktif WhatsApp</label>
                            <input type="tel" x-model="whatsapp" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: 81234567890">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Gmail Aktif</label>
                            <input type="email" x-model="gmail" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="contoh@gmail.com">
                        </div>
                        {{-- Input Kode Promo --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Kode Promo</label>
                            <input type="text" x-model="kodePromo" class="w-full border border-green-400 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Masukkan kode promo Anda">
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="nextStep(2)" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold">Lanjut</button>
                        </div>
                    </form>
                </div>
            
                <!-- STEP 2: PILIH JURUSAN -->
                <div x-show="step === 2" x-transition.opacity>
                     <h2 class="text-xl font-semibold mb-6 text-gray-800">Langkah 2: Pilih Program Studi</h2>
                    <form class="space-y-6">
                        <div class="bg-slate-50 border border-gray-200 rounded-xl p-6">
                            <label class="block text-gray-700 font-medium mb-3">Pilih Program Studi:</label>
                            <select x-model="jurusan" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="" disabled selected>-- Pilih salah satu --</option>
                                <optgroup label="Program Sarjana (S1)">
                                    <option value="Teknik Informatika">S1 - Teknik Informatika</option>
                                    <option value="Sistem Informasi">S1 - Sistem Informasi</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="flex justify-between pt-4">
                            <button type="button" @click="step = 1" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300">Kembali</button>
                            <button type="button" @click="nextStep(3)" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">Lanjut</button>
                        </div>
                    </form>
                </div>
                
                {{-- SISA STEP 3, 4, 5... --}}
            </div>
        </div>
    </div>
    
    <script>
    // Pastikan skrip Alpine.js Anda ada di sini
    function registerForm() {
        return {
            step: 1,
            nama: '',
            whatsapp: '',
            gmail: '',
            kodePromo: '',
            jurusan: '',
            // ... sisa data Anda
            
            nextStep(n) { this.step = n; },
            // ... sisa fungsi Anda
        }
    }
    </script>
</body>
</html>