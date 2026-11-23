<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'INVENTORYPRO') - Sistem Manajemen Gudang Laravel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased">
    @php
        $authUser = Auth::user() ?? \App\Models\User::where('role', 'admin')->first();
        $isAdmin = $authUser ? $authUser->isAdmin() : true;
    @endphp

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-sm backdrop-blur-md bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition">
                            <i class="fa-solid fa-boxes-stacked text-base"></i>
                        </div>
                        <div>
                            <span class="text-base font-extrabold tracking-tight bg-gradient-to-r from-slate-900 to-indigo-900 bg-clip-text text-transparent">INVENTORYPRO</span>
                            <span class="text-[10px] font-bold text-indigo-600 block uppercase tracking-wider -mt-1">Laravel P5</span>
                        </div>
                    </a>

                    <!-- Navigation Links (Desktop) -->
                    <nav class="hidden md:flex items-center gap-1 ml-6">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-chart-pie mr-1.5"></i> Dashboard
                        </a>
                        <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('products.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-box mr-1.5"></i> Master Produk
                        </a>
                        <a href="{{ route('stock.in') }}" class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('stock.in') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-circle-arrow-down mr-1.5 text-indigo-600"></i> Barang Masuk
                        </a>
                        <a href="{{ route('stock.out') }}" class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('stock.out') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-circle-arrow-up mr-1.5 text-amber-600"></i> Barang Keluar
                        </a>
                        <a href="{{ route('stock.history') }}" class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('stock.history') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Mutasi
                        </a>
                        @if($isAdmin)
                            <a href="{{ route('stock.print') }}" target="_blank" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                                <i class="fa-solid fa-print mr-1.5"></i> Cetak Laporan
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Right Side: User & Role Switcher -->
                <div class="flex items-center gap-3">
                    <!-- Switch Role Button for Demonstration -->
                    <div class="hidden sm:flex items-center bg-slate-100 p-1 rounded-xl text-[11px] font-bold border border-slate-200">
                        <span class="text-slate-400 px-2">Role:</span>
                        <a href="{{ route('switch.role', 'admin') }}" class="px-2.5 py-1 rounded-lg transition {{ $isAdmin ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                            Admin
                        </a>
                        <a href="{{ route('switch.role', 'staff') }}" class="px-2.5 py-1 rounded-lg transition {{ !$isAdmin ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                            Staff
                        </a>
                    </div>

                    <!-- User Profile Dropdown / Pill -->
                    <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center text-xs">
                            {{ substr($authUser->name ?? 'User', 0, 1) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-xs font-bold text-slate-800 leading-none">{{ $authUser->name ?? 'Tamu' }}</div>
                            <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded {{ $isAdmin ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $authUser->role ?? 'admin' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden flex overflow-x-auto gap-2 px-4 py-2 border-t border-slate-100 bg-slate-50/50">
            <a href="{{ route('dashboard') }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Dashboard</a>
            <a href="{{ route('products.index') }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Produk</a>
            <a href="{{ route('stock.in') }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('stock.in') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Barang Masuk</a>
            <a href="{{ route('stock.out') }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('stock.out') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Barang Keluar</a>
            <a href="{{ route('stock.history') }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('stock.history') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-700' }}">Riwayat</a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Flash Alert Component -->
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" />
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            <p class="font-medium">INVENTORYPRO &bull; Pertemuan 5 Laravel Fundamental MVC</p>
            <p class="text-[11px] text-slate-400 mt-1">Implementasi Database Migration, Eloquent ORM, Blade Templates, dan ACID Transactions</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
