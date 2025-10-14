<!-- {{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
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
            {{-- Input nama yang akan difilter --}}
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
                       class="block w-full border-gray-800 h-10 rounded-none rounded-r-md shadow-lg focus:border-indigo-700 focus:ring-indigo-500"
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
</x-guest-layout>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const isMobile = window.innerWidth < 768;

    const driver = new Driver({
        animate: true,
        opacity: 0.75,
        padding: 8,
        allowClose: false,
        stageBackground: '#ffffff',
    });

    driver.defineSteps([
        {
            element: '#name',
            popover: {
                title: 'Isi Nama Lengkap',
                description: 'Masukkan nama lengkap Anda sesuai identitas.',
                position: isMobile ? 'bottom' : 'right'
            }
        },
        {
            element: '#email',
            popover: {
                title: 'Isi Email Aktif',
                description: 'Gunakan email yang valid untuk verifikasi akun.',
                position: isMobile ? 'bottom' : 'right'
            }
        },
        {
            element: '#phone',
            popover: {
                title: 'Isi Nomor Telepon Aktif',
                description: 'Gunakan Nomor Telepon yang valid.',
                position: isMobile ? 'bottom' : 'right'
            }
        },
        {
            element: '#password',
            popover: {
                title: 'Buat Kata Sandi',
                description: 'Gunakan kombinasi huruf, angka, dan simbol agar lebih aman.',
                position: isMobile ? 'bottom' : 'right'
            }
        },
        {
            element: '#password_confirmation',
            popover: {
                title: 'Konfirmasi Kata Sandi',
                description: 'Ulangi kata sandi yang sudah dibuat agar cocok.',
                position: isMobile ? 'bottom' : 'right'
            }
        },
        {
            element: 'button[type="submit"]',
            popover: {
                title: 'Klik Daftar',
                description: 'Setelah semua data benar, klik tombol ini untuk mendaftar.',
                position: 'top'
            }
        }
    ]);
    
    //driver.start();

    // === KODE BARU UNTUK FILTER INPUT NAMA ===
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function(e) {
            // Mengganti karakter apa pun yang bukan huruf (a-z, A-Z) atau spasi (\s) dengan string kosong
            e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
        });
    }
    // === AKHIR KODE BARU ===
    
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const registerForm = document.querySelector('form'); // Mengambil form untuk submit event

    const validatePhone = () => {
        if (phoneInput.value.startsWith('0')) {
            phoneInput.value = phoneInput.value.substring(1);
        }

        const phoneRegex = /^8[1-9][0-9]{7,10}$/;
        const isValid = phoneRegex.test(phoneInput.value);
        
        if (phoneInput.value === '') {
            phoneError.textContent = '';
            phoneInput.classList.remove('border-red-500', 'focus:ring-red-500');
            return true;
        }

        if (isValid) {
            phoneError.textContent = '';
            phoneInput.classList.remove('border-red-500', 'focus:ring-red-500');
            phoneInput.classList.add('border-gray-300', 'focus:ring-blue-500');
        } else {
            phoneError.textContent = 'Nomor harus dimulai dengan angka 8 (contoh: 81234567890) dan tersambung dengan whatsapp.';
            phoneInput.classList.remove('border-gray-300', 'focus:ring-blue-500');
            phoneInput.classList.add('border-red-500', 'focus:ring-red-500');
        }
        
        return isValid;
    };

    if (phoneInput) {
        phoneInput.addEventListener('input', validatePhone);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            if (!validatePhone()) {
                event.preventDefault();
                phoneInput.focus();
            }
        });
    }
});
</script> -->



{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
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
            {{-- Input nama yang akan difilter --}}
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

        {{-- PERUBAHAN UNTUK MENGGUNAKAN 0 BUKAN +62 --}}
        <div class="mt-4">
            <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon</label>
            {{-- Input 'tel' sekarang berdiri sendiri tanpa prefix +62 --}}
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                   placeholder="081234567890"
                   pattern="^08[0-9]{8,11}$" {{-- Pattern diubah untuk menerima 08 --}}
                   required />
            <div id="phone-error" class="text-sm text-red-600 mt-2"></div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        {{-- AKHIR PERUBAHAN --}}

        <div class="mb-4 mt-4">
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
</x-guest-layout>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // ... (kode Driver.js Anda tidak berubah)
    const isMobile = window.innerWidth < 768;
    const driver = new Driver({
        animate: true,
        opacity: 0.75,
        padding: 8,
        allowClose: false,
        stageBackground: '#ffffff',
    });
    driver.defineSteps([
        { element: '#name', popover: { title: 'Isi Nama Lengkap', description: 'Masukkan nama lengkap Anda sesuai identitas.', position: isMobile ? 'bottom' : 'right' } },
        { element: '#email', popover: { title: 'Isi Email Aktif', description: 'Gunakan email yang valid untuk verifikasi akun.', position: isMobile ? 'bottom' : 'right' } },
        { element: '#phone', popover: { title: 'Isi Nomor Telepon Aktif', description: 'Gunakan Nomor Telepon yang valid dengan format 08...', position: isMobile ? 'bottom' : 'right' } },
        { element: '#password', popover: { title: 'Buat Kata Sandi', description: 'Gunakan kombinasi huruf, angka, dan simbol agar lebih aman.', position: isMobile ? 'bottom' : 'right' } },
        { element: '#password_confirmation', popover: { title: 'Konfirmasi Kata Sandi', description: 'Ulangi kata sandi yang sudah dibuat agar cocok.', position: isMobile ? 'bottom' : 'right' } },
        { element: 'button[type="submit"]', popover: { title: 'Klik Daftar', description: 'Setelah semua data benar, klik tombol ini untuk mendaftar.', position: 'top' } }
    ]);
    //driver.start();

    // === Filter input nama (tidak berubah) ===
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
        });
    }

    // === PERUBAHAN SCRIPT VALIDASI TELEPON DIMULAI DI SINI ===
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const registerForm = document.querySelector('form');

    const validatePhone = () => {
        // Regex diubah untuk memvalidasi nomor yang dimulai dengan "08"
        // dan memiliki total 10 hingga 13 digit.
        const phoneRegex = /^08[1-9][0-9]{7,10}$/; 
        const isValid = phoneRegex.test(phoneInput.value);
        
        if (phoneInput.value === '') {
            phoneError.textContent = '';
            phoneInput.classList.remove('border-red-500', 'focus:ring-red-500');
            return true;
        }

        if (isValid) {
            phoneError.textContent = '';
            phoneInput.classList.remove('border-red-500', 'focus:ring-red-500');
            phoneInput.classList.add('border-gray-300', 'focus:ring-blue-500');
        } else {
            // Pesan error disesuaikan dengan format baru
            phoneError.textContent = 'Format nomor salah. Harus dimulai dengan 08 dan terhubung ke WhatsApp.';
            phoneInput.classList.remove('border-gray-300', 'focus:ring-blue-500');
            phoneInput.classList.add('border-red-500', 'focus:ring-red-500');
        }
        
        return isValid;
    };

    if (phoneInput) {
        phoneInput.addEventListener('input', validatePhone);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            if (!validatePhone()) {
                event.preventDefault(); // Mencegah form dikirim jika nomor tidak valid
                phoneInput.focus();
            }
        });
    }
    // === AKHIR PERUBAHAN SCRIPT ===
});
</script>