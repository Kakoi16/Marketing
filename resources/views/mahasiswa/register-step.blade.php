<!DOCTYPE html>
<html lang="id" x-data="registerForm()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mahasiswa Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; /* slate-50 */
        }
        /* Menambahkan animasi halus pada progress bar */
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans">
    
    <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-br from-blue-600 to-indigo-700 opacity-20 -z-10"></div>

    <div class="max-w-4xl mx-auto my-12 lg:my-24">
        <div class="text-center mb-8">
            <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-800">Formulir Pendaftaran Online</h1>
            <p class="mt-2 text-gray-500">Ikuti langkah-langkah di bawah ini untuk menyelesaikan pendaftaran.</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 lg:p-10">
        
            <div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div class="bg-blue-600 h-2 rounded-full progress-bar" :style="`width: ${(step - 1) / 5 * 100}%`"></div>
                </div>
                <div class="flex justify-between">
                    <template x-for="(label, index) in ['Data Diri', 'Jurusan', 'Berkas', 'Konfirmasi', 'Pembayaran', 'Selesai']" :key="index">
                        <div class="w-1/6">
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

            <div>
                <div x-show="step === 1" x-transition.opacity>
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Langkah 1: Isi Data Diri Anda</h2>
                    <form class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                            <input type="text" x-model="nama"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Masukkan nama lengkap anda">
                        </div>
        
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Nomor Aktif WhatsApp</label>
                            <input type="tel" x-model="whatsapp"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Contoh: 81234567890">
                            <p class="text-sm text-gray-500 mt-1">*Masukkan tanpa awalan 0 atau +62</p>
                        </div>
        
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Gmail Aktif</label>
                            <input type="email" x-model="gmail"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="contoh@gmail.com">
                        </div>
        
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="nextStep(2)"
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold">
                                Lanjut
                            </button>
                        </div>
                    </form>
                </div>
            
                <div x-show="step === 2" x-transition.opacity>
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Langkah 2: Pilih Program Studi</h2>
                    <form class="space-y-6">
                        <div class="bg-slate-50 border border-gray-200 rounded-xl p-6">
                            <label class="block text-gray-700 font-medium mb-3">Pilih Program Studi:</label>
                            <select x-model="jurusan" @change="updateIjazahLabel()"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="" disabled selected>-- Pilih salah satu --</option>
                                <optgroup label="Program Sarjana (S1)">
                                    <option value="Teknik Informatika">S1 - Teknik Informatika</option>
                                    <option value="Sistem Informasi">S1 - Sistem Informasi</option>
                                    <option value="Manajemen">S1 - Manajemen</option>
                                    <option value="Akuntansi">S1 - Akuntansi</option>
                                    <option value="Ilmu Hukum">S1 - Ilmu Hukum</option>
                                    <option value="Teknik Industri">S1 - Teknik Industri</option>
                                </optgroup>
                                <optgroup label="Program Magister (S2)">
                                    <option value="Magister Manajemen">S2 - Magister Manajemen</option>
                                </optgroup>
                            </select>
                        </div>
        
                        <div class="flex justify-between pt-4">
                            <button type="button" @click="step = 1"
                                class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition font-semibold">
                                Kembali
                            </button>
                            <button type="button" @click="nextStep(3)"
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold">
                                Lanjut
                            </button>
                        </div>
                    </form>
                </div>
                
                <div x-show="step === 3" x-transition.opacity>
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Langkah 3: Unggah Dokumen Pendaftaran</h2>
                    <form id="uploadForm" class="space-y-6">
                        <div class="bg-slate-50 p-6 border border-gray-200 rounded-xl space-y-5">
                            <div>
                                <label class="block font-medium text-gray-700 mb-2">1. Unggah Kartu Keluarga (KK):</label>
                                <input type="file" name="file_kk" accept=".pdf, .jpg, .jpeg, .png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-2">2. Unggah Ijazah:</label>
                                <input type="file" name="file_ijazah" accept=".pdf, .jpg, .jpeg, .png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                        <div class="flex justify-between pt-4">
                            <button type="button" @click="step = 2" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition font-semibold">Kembali</button>
                            <button type="button" @click="validateWithOCR()" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm font-semibold">Validasi & Lanjutkan</button>
                        </div>
                    </form>
                </div>
                
                <div x-show="step === 4" x-transition.opacity>
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Hasil Pindai Dokumen & Formulir Koreksi</h2>
                    <template x-if="ocrResults && (ocrResults.kk || ocrResults.ijazah)">
                        <form id="correctionForm" class="space-y-6">
                            @csrf
                            <template x-if="ocrResults.kk">
                                <div class="bg-white shadow p-5 rounded-xl border border-gray-200">
                                    <h3 class="text-lg font-semibold mb-3">Kartu Keluarga</h3>
                                    <template x-for="(value, key) in ocrResults.kk.data" :key="key">
                                        <div class="mb-2">
                                            <label class="block text-gray-700 capitalize" x-text="key.replace(/_/g, ' ')"></label>
                                            <input type="text" :name="'kk[' + key + ']'" :value="value"
                                                class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </template>
                                </div>
                            </template>
                
                            <template x-if="ocrResults.ijazah">
                                <div class="bg-white shadow p-5 rounded-xl border border-gray-200">
                                    <h3 class="text-lg font-semibold mb-3">Ijazah</h3>
                                    <template x-for="(value, key) in ocrResults.ijazah.data" :key="key">
                                        <div class="mb-2">
                                            <label class="block text-gray-700 capitalize" x-text="key.replace(/_/g, ' ')"></label>
                                            <input type="text" :name="'ijazah[' + key + ']'" :value="value"
                                                class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </template>
                                </div>
                            </template>
                
                            <div class="flex justify-between pt-6">
                                <button type="button" @click="step = 3"
                                    class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition font-semibold">Kembali</button>
                                
                                <button type="button" @click="submitCorrections()"
                                    class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm font-semibold">
                                    Simpan & Lanjutkan
                                </button>
                            </div>
                        </form>
                    </template>
                    <template x-if="!ocrResults || (!ocrResults.kk && !ocrResults.ijazah)">
                        <p class="text-gray-500 text-center">Belum ada hasil pindai.</p>
                    </template>
                </div>
                
                <div x-show="step === 5" x-transition.opacity>
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 text-center">Virtual Account Pembayaran</h2>
                    <div class="bg-slate-50 p-6 border border-gray-200 rounded-xl shadow-sm text-center space-y-4">
                        <p class="text-gray-700">Silakan lakukan pembayaran ke Virtual Account berikut:</p>
                        <h3 class="text-2xl font-semibold text-blue-600 tracking-wider" x-text="virtualAccount"></h3>
                        <p class="text-gray-600 text-sm">Nominal: Rp 350.000 (Biaya Pendaftaran)</p>
                    </div>
                    <div class="flex justify-between pt-6">
                        <button type="button" @click="step = 4"
                            class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Kembali
                        </button>
                        <button type="button" @click="generateUsername()"
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold">
                            Saya Sudah Bayar
                        </button>
                    </div>
                </div>
                
                <div x-show="step === 6" x-transition.opacity>
                    <div class="text-center py-10">
                        <div class="flex justify-center mb-4">
                            <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Pendaftaran Berhasil!</h2>
                        <p class="text-gray-600">Selamat datang, <b x-text="nama"></b> 🎉</p>
                        <p class="text-gray-700 mt-4">Username Anda:</p>
                        <h3 class="text-xl font-bold text-blue-600" x-text="username"></h3>
                        <button type="button" @click="finishForm()"
                            class="mt-6 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function registerForm() {
        return {
            step: 1,
            nama: '',
            whatsapp: '',
            gmail: '',
            jurusan: '',
            labelIjazah: 'Unggah Ijazah SMK:',
            virtualAccount: '',
            username: '',
            ocrResults: null,
    
            // Fungsi untuk pindah ke step berikutnya
            nextStep(n) {
                this.step = n;
            },
    
            // Update label ijazah otomatis sesuai jurusan
            updateIjazahLabel() {
                this.labelIjazah = (this.jurusan === 'Magister Manajemen')
                    ? 'Unggah Ijazah S1:'
                    : 'Unggah Ijazah SMK:';
            },
    
            // Upload file KK & Ijazah
            async uploadFiles() {
                const formData = new FormData(document.getElementById('uploadForm'));
                try {
                    const res = await fetch("{{ route('mahasiswa.upload') }}", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire('Sukses', data.message, 'success');
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', e.message, 'error');
                }
            },
            
            async submitCorrections() {
                const formData = new FormData(document.getElementById('correctionForm'));

                Swal.fire({
                    title: 'Menyimpan Data...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch("{{ route('ocr.save') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.close();
                        this.generateVA(); // Lanjut ke step 5 (Virtual Account)
                    } else {
                        Swal.fire('Gagal', data.message || 'Gagal menyimpan data.', 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Terjadi kesalahan: ' + e.message, 'error');
                }
            },
    
            // Validasi ke OCRController
            async validateWithOCR() {
                const formData = new FormData(document.getElementById('uploadForm'));

                // Tampilkan loading
                Swal.fire({
                    title: 'Memindai Dokumen...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const res = await fetch("{{ route('mahasiswa.validate') }}", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: formData
                    });
                    const data = await res.json();

                    if (data.success) {
                        Swal.close(); // Tutup loading Swal
                        // Simpan hasil ke variabel lokal agar tampil tanpa reload
                        this.ocrResults = data.results || {};
                        this.step = 4;
                    } else {
                        Swal.fire('Tidak Valid', data.message, 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Gagal terhubung ke server: ' + e.message, 'error');
                }
            },
    
            // Simulasi generate Virtual Account
            generateVA() {
                this.virtualAccount = '8888 ' + Math.floor(1000 + Math.random() * 9000) + ' ' + Math.floor(1000 + Math.random() * 9000);
                this.step = 5;
            },
    
            // Simulasi generate Username
            generateUsername() {
                this.username = this.nama.toLowerCase().replace(/\s+/g, '') + Math.floor(Math.random() * 1000);
                this.step = 6;
            },
    
            // Selesai
            finishForm() {
                Swal.fire('Selesai!', 'Pendaftaran berhasil disimpan.', 'success');
                // Arahkan ke halaman utama atau login setelah selesai
                setTimeout(() => {
                    window.location.href = '/';
                }, 1500);
            }
        }
    }
    </script>
</body>
</html>