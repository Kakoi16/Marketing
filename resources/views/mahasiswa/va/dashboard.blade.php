<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div id="app">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Pembayaran
                </h2>
            </div>
        </header>

        <main>
            <div class="py-12">
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            
                            <div class="text-center mb-4">
                                <h3 class="text-2xl font-bold text-gray-800">Selesaikan Pembayaran Anda</h3>
                                <p class="text-gray-600">Gunakan detail di bawah ini untuk membayar biaya pendaftaran.</p>
                            </div>

                            <div class="border rounded-lg p-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Total Pembayaran:</span>
                                    <span class="font-bold text-xl text-indigo-600">
                                        Rp 300.000
                                    </span>
                                </div>
                                <hr>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Nomor Virtual Account:</span>
                                    <span class="font-semibold text-gray-800">9884162500123456</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Batas Waktu Pembayaran:</span>
                                    <span class="font-semibold text-red-500">
                                        08 Oktober 2025, 03:21 WIB
                                    </span>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-700">Cara Pembayaran:</h4>
                                <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                                    <li>Buka aplikasi BNI Mobile Banking, ATM BNI, atau Internet Banking BNI.</li>
                                    <li>Pilih menu Transfer, lalu pilih Virtual Account Billing.</li>
                                    <li>Masukkan nomor Virtual Account di atas.</li>
                                    <li>Pastikan detail transaksi sudah sesuai.</li>
                                    <li>Selesaikan pembayaran.</li>
                                </ul>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>