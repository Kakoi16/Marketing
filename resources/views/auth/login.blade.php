{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    {{-- Status Sesi Autentikasi --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- === ANIMASI MONYET === --}}
    <div class="mx-auto w-40 h-40 relative mb-4">
        {{-- Badan & Kepala Monyet --}}
        <div class="w-full h-full bg-yellow-900 rounded-full"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-amber-700 rounded-full"></div>
        
        {{-- Telinga --}}
        <div class="absolute w-12 h-12 bg-amber-700 rounded-full top-8 -left-2"></div>
        <div class="absolute w-12 h-12 bg-amber-700 rounded-full top-8 -right-2"></div>
        <div class="absolute w-10 h-10 bg-yellow-900 rounded-full top-9 -left-1"></div>
        <div class="absolute w-10 h-10 bg-yellow-900 rounded-full top-9 -right-1"></div>

        {{-- Wajah --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/4 w-28 h-20 bg-orange-200 rounded-full"></div>
        
        {{-- Mata --}}
        <div class="absolute top-[55%] left-1/2 -translate-x-1/2 -translate-y-1/2 flex gap-4">
            <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center"><div class="w-3 h-3 bg-black rounded-full"></div></div>
            <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center"><div class="w-3 h-3 bg-black rounded-full"></div></div>
        </div>

        {{-- Tangan untuk Menutup Mata --}}
        <div id="left-hand" class="absolute w-12 h-12 bg-amber-700 rounded-lg top-28 left-8 transition-transform duration-300 ease-in-out"></div>
        <div id="right-hand" class="absolute w-12 h-12 bg-amber-700 rounded-lg top-28 right-8 transition-transform duration-300 ease-in-out"></div>
    </div>
    {{-- === AKHIR ANIMASI MONYET === --}}

    <h2 class="mb-3 text-3xl font-bold text-center">SELAMAT DATANG!</h2>
    <p class="mb-6 font-light text-gray-500 text-center">
        Masuk ke akun Anda untuk melanjutkan.
    </p>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Alamat Email</label>
            <input type="email" id="email" name="email" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                   placeholder="contoh@gmail.com">
        </div>
        
        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi</label>
            <div class="relative">
                <input type="password" id="password" name="password" required
                       class="w-full px-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                       placeholder="Masukan Kata Sandi">
                
                <!-- Tombol Ikon Mata -->
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500">
                    <svg id="eye-open" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963 7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-closed" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-gray-700">Ingat Saya</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>
        
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 ease-in-out">
            Login
        </button>
        
        <div class="mt-6 text-center">
            <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-blue-600 hover:underline">
                Belum punya akun? Daftar
            </a>
        </div>
    </form>
</x-guest-layout>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Kode Driver.js
    const isMobile = window.innerWidth < 768;
    const driver = new Driver({
        animate: true,
        opacity: 0.75,
        padding: 8,
        allowClose: false,
        stageBackground: '#ffffff',
    });
    // driver.defineSteps([...]); // Asumsikan Anda memiliki step-step ini
    // driver.start();


    // === KODE UNTUK REVEAL PASSWORD (DITAMBAHKAN KEMBALI) ===
    const togglePasswordButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpenIcon = document.getElementById('eye-open');
    const eyeClosedIcon = document.getElementById('eye-closed');

    if (togglePasswordButton) {
        togglePasswordButton.addEventListener('click', function () {
            // Cek tipe input saat ini
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon yang ditampilkan
            eyeOpenIcon.classList.toggle('hidden');
            eyeClosedIcon.classList.toggle('hidden');
        });
    }


    // === KODE UNTUK ANIMASI MONYET ===
    const passwordInputForMonkey = document.getElementById('password');
    const leftHand = document.getElementById('left-hand');
    const rightHand = document.getElementById('right-hand');

    if (passwordInputForMonkey) {
        // Saat kolom password di-klik/fokus
        passwordInputForMonkey.addEventListener('focus', function() {
            leftHand.style.transform = 'translateY(-55px) rotate(-15deg)';
            rightHand.style.transform = 'translateY(-55px) rotate(15deg)';
        });

        // Saat klik di luar kolom password
        passwordInputForMonkey.addEventListener('blur', function() {
            leftHand.style.transform = 'translateY(0) rotate(0)';
            rightHand.style.transform = 'translateY(0) rotate(0)';
        });
    }
});
</script>