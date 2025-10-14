{{-- File: resources/views/layouts/mahasiswa-layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PMB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Inter', sans-serif; } .sidebar-link:hover { background-color: #3b82f6; } </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">
                PMB Online
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center p-3 rounded-lg sidebar-link {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-600' : '' }}">
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('mahasiswa.biodata.create') }}" class="flex items-center p-3 rounded-lg sidebar-link {{ request()->routeIs('mahasiswa.biodata.create') ? 'bg-blue-600' : '' }}">
                    <span>Biodata</span>
                </a>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <div class="font-semibold">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-400 hover:text-red-300">Logout</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>