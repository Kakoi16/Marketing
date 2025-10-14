{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    {{-- Kita bungkus semuanya dalam Alpine.js untuk mengontrol visibilitas --}}
    <div x-data="{ showPromo: false }">
        <h2 class="mb-3 text-3xl font-bold">MULAI SEKARANG!</h2>
        <p class="mb-6 font-light text-gray-500">
            Buat akun untuk melanjutkan.
        </p>
        @if(session('error'))
            <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg" role="alert">
                <span class="font-medium">Oops!</span> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                       placeholder="Masukan Nama Lengkap">
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Alamat Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                       placeholder="contoh@gmail.com">
            </div>
            <div class="mt-4">
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon</label>
                <div class="flex mt-1">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                        +62
                    </span>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                           class="block w-full border-gray-300 h-10 rounded-none rounded-r-md shadow-sm focus:border-indigo-700 focus:ring-indigo-500"
                           placeholder="81234567890"
                           pattern="^8[0-9]{8,11}$"
                           required />
                </div>
                <div id="phone-error" class="text-sm text-red-600 mt-2"></div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                       placeholder="Masukan Kata Sandi">
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                       placeholder="Konfirmasi Kata Sandi">
            </div>

            <!-- === PERUBAHAN DI SINI === -->
            <!-- Tombol untuk menampilkan/menyembunyikan input kode promo -->
            <div class="mb-4 text-sm">
                <a href="#" @click.prevent="showPromo = !showPromo" class="text-blue-600 hover:underline">
                    <span x-show="!showPromo"> punya kode promo? Klik di sini</span>
                    <span x-show="showPromo">Tutup kolom kode promo</span>
                </a>
            </div>

            <!-- Input Kode Promo (hanya muncul jika diklik) -->
            <div x-show="showPromo" x-transition class="mb-6">
                <label for="promo_code" class="block mb-2 text-sm font-medium text-gray-700">Kode Promo</label>
                <input id="promo_code" class="block mt-1 w-full border-green-400 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" type="text" name="promo_code" placeholder="Masukkan kode promo Anda" />
            </div>
            <!-- === AKHIR PERUBAHAN === -->

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 ease-in-out">
                Daftar
            </button>
            
            <div class="mt-6 text-center">
                <a href="/login" class="text-sm text-gray-500 hover:text-blue-600 hover:underline">
                    Sudah Punya Akun?
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>

<script>
    // Kode JavaScript Anda yang sudah ada tidak diubah dan tetap berfungsi
    document.addEventListener("DOMContentLoaded", function() {
        // ... (kode Driver.js dan validasi telepon Anda tetap di sini) ...
    });
</script>