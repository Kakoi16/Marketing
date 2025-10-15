<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Pembayaran</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js (Dibutuhkan untuk Tab Petunjuk Pembayaran) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @layer base {
            html {
                font-family: 'Inter', sans-serif;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bni-teal': '#00A1AF',
                        'bni-orange': '#F78F1E',
                        'bni-dark-blue': '#003366',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">

        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="https://vectorez.biz.id/wp-content/uploads/2023/10/Logo-Bank-Negara-Indonesia-BNI.png" alt="BNI Logo" class="h-4">
                    <img src="http://pmb.kakoi.my.id/images/PENMABAR.gif" alt="BNI Logo" class="h-10">
                    <span class="font-bold text-xl text-bni-dark-blue">PENMABAR</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-sm font-semibold text-gray-600 hover:text-bni-dark-blue transition-colors">
                        Logout
                    </a>
                </form>
            </div>
        </header>

        <main class="flex-grow">
            <div class="py-12">
                <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

                    @if(isset($error_message))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <p>{{ $error_message }}</p>
                    </div>
                    @elseif(isset($user))
                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                        <div class="p-8 space-y-6">

                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-bni-dark-blue">Instruksi Pembayaran</h2>
                                <p class="text-gray-500 mt-1">Selesaikan pembayaran sebelum batas waktu.</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-600">Jumlah Tagihan</p>
                                <p class="text-3xl font-extrabold text-bni-orange tracking-tight">
                                    Rp {{ number_format($user->va_amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-center space-x-2 bg-red-50 text-red-700 p-3 rounded-lg">
                                <svg xmlns="http://www.w.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-semibold">
                                    Bayar sebelum: <strong>{{ \Carbon\Carbon::parse($user->va_expired_at)->translatedFormat('d F Y, H:i') }} WIB</strong>
                                </p>
                            </div>

                            <div class="border border-dashed border-gray-300 rounded-lg p-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-bni-dark-blue">BNI Virtual Account</span>
                                    <img src="https://vectorez.biz.id/wp-content/uploads/2023/10/Logo-Bank-Negara-Indonesia-BNI.png" alt="BNI Logo" class="h-4">
                                </div>
                                <div class="flex justify-between items-center bg-gray-100 p-3 rounded-md">
                                    <span id="va-number" class="text-xl font-bold text-gray-800 tracking-wider">{{ $user->va_number }}</span>
                                    <button
                                        id="copy-button"
                                        onclick="copyVirtualAccount()"
                                        class="flex items-center space-x-1.5 text-sm font-semibold text-bni-teal hover:text-bni-dark-blue transition-colors px-3 py-1 rounded-md hover:bg-bni-teal/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span id="copy-text">Salin</span>
                                        <span id="copied-text" style="display: none;">Tersalin!</span>
                                    </button>
                                </div>
                            </div>

                            {{-- ================= AWAL BLOK PETUNJUK PEMBAYARAN ================= --}}
                            <div x-data="{ activeTab: 'mobile' }">
                                <h4 class="font-bold text-gray-700 mb-3">Petunjuk Pembayaran</h4>

                                <div class="mb-4 border-b border-gray-200">
                       <nav 
    class="-mb-px flex space-x-4 overflow-x-auto pb-3
           [&::-webkit-scrollbar]:h-1.5
           [&::-webkit-scrollbar-track]:bg-teal-50
           [&::-webkit-scrollbar-thumb]:bg-bni-teal [&::-webkit-scrollbar-thumb]:rounded-full"
    aria-label="Tabs">
    
    <button @click="activeTab = 'mobile'" :class="{ 'border-bni-teal text-bni-teal': activeTab === 'mobile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'mobile' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm focus:outline-none">Mobile Banking</button>
    <button @click="activeTab = 'atm'" :class="{ 'border-bni-teal text-bni-teal': activeTab === 'atm', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'atm' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm focus:outline-none">ATM BNI</button>
    <button @click="activeTab = 'ibanking'" :class="{ 'border-bni-teal text-bni-teal': activeTab === 'ibanking', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ibanking' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm focus:outline-none">Internet Banking</button>
    <button @click="activeTab = 'other'" :class="{ 'border-bni-teal text-bni-teal': activeTab === 'other', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'other' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm focus:outline-none">Bank Lain</button>
</nav>
                                </div>

                                <div class="text-sm text-gray-600 space-y-2 leading-relaxed">
                                    <div x-show="activeTab === 'mobile'" x-transition>
                                        <ol class="list-decimal pl-5 space-y-1">
                                            <li>Buka aplikasi <strong>BNI Mobile Banking</strong> dan login.</li>
                                            <li>Pilih menu <strong>"Transfer"</strong>.</li>
                                            <li>Pilih menu <strong>"Virtual Account Billing"</strong>.</li>
                                            <li>Masukkan nomor Virtual Account Anda (contoh: {{ $user->va_number }}) pada kolom yang tersedia.</li>
                                            <li>Pilih rekening debet dan lanjutkan.</li>
                                            <li>Tagihan akan muncul secara otomatis. Pastikan data sudah sesuai.</li>
                                            <li>Konfirmasi transaksi dan masukkan <strong>Password Transaksi</strong> Anda.</li>
                                            <li>Pembayaran Anda Selesai.</li>
                                        </ol>
                                    </div>
                                    <div x-show="activeTab === 'atm'" x-transition style="display: none;">
                                        <ol class="list-decimal pl-5 space-y-1">
                                            <li>Masukkan Kartu ATM dan PIN Anda.</li>
                                            <li>Pilih <strong>"Menu Lain"</strong>, lalu pilih <strong>"Transfer"</strong>.</li>
                                            <li>Pilih jenis rekening <strong>"Dari Rekening Tabungan"</strong>.</li>
                                            <li>Pilih <strong>"Virtual Account Billing"</strong>.</li>
                                            <li>Masukkan nomor Virtual Account Anda (contoh: {{ $user->va_number }}).</li>
                                            <li>Tagihan yang harus dibayarkan akan muncul pada layar konfirmasi.</li>
                                            <li>Pastikan data sudah sesuai, lalu lanjutkan transaksi.</li>
                                            <li>Transaksi Anda telah selesai. Simpan struk sebagai bukti pembayaran.</li>
                                        </ol>
                                    </div>
                                    <div x-show="activeTab === 'ibanking'" x-transition style="display: none;">
                                        <ol class="list-decimal pl-5 space-y-1">
                                            <li>Buka situs <strong>ibank.bni.co.id</strong> dan login dengan User ID dan Password Anda.</li>
                                            <li>Pilih menu <strong>"Transfer"</strong>.</li>
                                            <li>Pilih <strong>"Virtual Account Billing"</strong>.</li>
                                            <li>Masukkan nomor Virtual Account Anda (contoh: {{ $user->va_number }}).</li>
                                            <li>Pilih rekening debet yang akan digunakan, lalu klik <strong>"Lanjut"</strong>.</li>
                                            <li>Tagihan akan muncul secara otomatis. Pastikan rincian pembayaran sudah benar.</li>
                                            <li>Masukkan kode otentikasi dari <strong>BNI e-Secure (Token)</strong> Anda.</li>
                                            <li>Pembayaran Anda berhasil.</li>
                                        </ol>
                                    </div>
                                    <div x-show="activeTab === 'other'" x-transition style="display: none;">
                                        <ol class="list-decimal pl-5 space-y-1">
                                            <li>Pilih menu <strong>"Transfer Antar Bank"</strong> atau <strong>"Transfer Online"</strong>.</li>
                                            <li>Masukkan kode bank <strong>BNI (009)</strong> atau pilih bank tujuan BNI.</li>
                                            <li>Masukkan nomor Virtual Account pada kolom rekening tujuan.</li>
                                            <li>Masukkan jumlah tagihan sesuai dengan yang tertera (Rp {{ number_format($user->va_amount, 0, ',', '.') }}). <strong>Jumlah harus sama persis</strong>.</li>
                                            <li>Kosongkan kolom nomor referensi jika ada.</li>
                                            <li>Konfirmasi rincian transfer Anda. Pastikan nama dan jumlah tagihan sesuai.</li>
                                            <li>Transaksi selesai.</li>
                                        </ol>
                                        <p class="mt-3 text-xs text-amber-700 bg-amber-50 p-2 rounded-md"><strong>Catatan:</strong> Pembayaran melalui bank lain mungkin dikenakan biaya administrasi. Waktu proses tergantung pada kebijakan bank pengirim.</p>
                                    </div>
                                </div>
                            </div>
                            {{-- ================= AKHIR BLOK PETUNJUK PEMBAYARAN ================= --}}

                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </main>

        <footer class="bg-white mt-auto">
            <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} PENMABAR. Semua hak cipta dilindungi.
            </div>
        </footer>
    </div>

    <script>
        function copyVirtualAccount() {
            const vaNumberElement = document.getElementById('va-number');
            const copyTextElement = document.getElementById('copy-text');
            const copiedTextElement = document.getElementById('copied-text');
            const copyButton = document.getElementById('copy-button');
            const vaNumber = vaNumberElement.innerText;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(vaNumber).then(() => {
                    showCopiedFeedback();
                }).catch(err => {
                    console.error('Gagal menyalin dengan API modern: ', err);
                    fallbackCopyTextToClipboard(vaNumber);
                });
            } else {
                fallbackCopyTextToClipboard(vaNumber);
            }

            function fallbackCopyTextToClipboard(text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.top = "0";
                textArea.style.left = "0";
                textArea.style.position = "fixed";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        showCopiedFeedback();
                    } else {
                        alert('Gagal menyalin nomor VA. Mohon salin secara manual.');
                    }
                } catch (err) {
                    console.error('Gagal menyalin dengan metode fallback: ', err);
                    alert('Gagal menyalin nomor VA. Mohon salin secara manual.');
                }
                document.body.removeChild(textArea);
            }

            function showCopiedFeedback() {
                copyTextElement.style.display = 'none';
                copiedTextElement.style.display = 'inline';
                copyButton.disabled = true;
                setTimeout(() => {
                    copyTextElement.style.display = 'inline';
                    copiedTextElement.style.display = 'none';
                    copyButton.disabled = false;
                }, 2000);
            }
        }
    </script>
</body>

</html>