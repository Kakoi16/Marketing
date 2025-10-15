{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <h2 class="mb-3 text-3xl font-bold">MULAI SEKARANG!</h2>
    <p class="mb-6 font-light text-gray-500">
        Buat akun untuk melanjutkan.
    </p>
   {{-- Notifikasi error lama --}}
    @if(session('error'))
        <div id="error-message" data-error="{{ session('error') }}"></div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
 const errorMessageDiv = document.getElementById('error-message');
    if (errorMessageDiv && errorMessageDiv.dataset.error) {
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: errorMessageDiv.dataset.error,
            confirmButtonText: 'OK',
            background: '#ffffff',
            color: '#333',
            confirmButtonColor: '#93c5fd',
            customClass: {
                confirmButton: 'swal2-confirm-button'
            }
        });
    }
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

<style>
.swal2-confirm-button {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 8px 22px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease-in-out !important;
}

.swal2-confirm-button:hover {
    background-color: #60a5fa !important;
}
</style>