<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
@extends('layouts.admin.app')

@section('title', 'Code OTP Promo')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="rounded-2xl bg-white dark:bg-gray-900 p-6 shadow-sm">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Buat Code OTP Promo</h2>
        <p class="mb-6 text-gray-500 dark:text-gray-400 text-sm">
            Gunakan halaman ini untuk membuat kode promo untuk CAMABA.
        </p>

        <!-- Form Buat Promo -->
        <form action="{{ route('admin.promocode.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <input type="text" name="description" placeholder="Contoh: Promo Registrasi 2025"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-2">
            </div>

            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Diskon (%)</label>
                <input type="number" name="discount" required min="1" max="100" value="10"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-2">
            </div>

            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tanggal Kadaluarsa</label>
                <input type="date" name="expired_at"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-2">
            </div>

            <button type="submit"
                class="bg-brand-500 hover:bg-brand-600 text-white font-semibold py-2 px-4 rounded-lg">
                🔑 Buat Kode Promo
            </button>
        </form>
    </div>

    <!-- Daftar Kode Promo -->
    <div class="mt-8 bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Daftar Kode Promo</h3>

        @if (session('success'))
            <div class="mb-4 text-green-600 font-medium">{{ session('success') }}</div>
        @endif

        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="border-b border-gray-300 dark:border-gray-700">
                <tr>
                    <th class="py-2">Kode</th>
                    <th>Deskripsi</th>
                    <th>Diskon</th>
                    <th>Kadaluarsa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <td class="py-2 font-mono">{{ $promo->code }}</td>
                        <td>{{ $promo->description ?? '-' }}</td>
                        <td>{{ $promo->discount }}%</td>
                        <td>{{ $promo->expired_at ? $promo->expired_at->format('d M Y') : '-' }}</td>
                        <td>
                            <form action="{{ route('admin.promocode.destroy', $promo->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kode promo ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-600 font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada kode promo</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

 <script src="{{ asset('assets/js/bundle.js') }}"></script>