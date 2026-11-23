@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8 text-white shadow-xl {{ $isAdmin ? 'bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-indigo-900/40' : 'bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 border border-emerald-900/40' }}">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $isAdmin ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' }}">
                        <i class="fa-solid {{ $isAdmin ? 'fa-shield-halved' : 'fa-clipboard-check' }}"></i>
                        {{ $isAdmin ? 'Dashboard Administrator' : 'Portal Operasional Staff Gudang' }}
                    </span>
                    <span class="text-xs text-slate-400">| {{ date('l, d F Y') }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                    Selamat Datang, {{ $user->name ?? 'Admin' }}! 👋
                </h1>
                <p class="text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                    {{ $isAdmin 
                        ? 'Pantau pergerakan arus barang, valuasi total aset persediaan, dan kelola katalog produk elektronik Anda.' 
                        : 'Kelola alur pencatatan barang masuk dan barang keluar, serta pantau persediaan fisik di gudang secara real-time.' }}
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                @if($isAdmin)
                    <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-blue-600/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Produk
                    </a>
                    <a href="{{ route('stock.print') }}" target="_blank" class="bg-slate-800/90 hover:bg-slate-700 text-slate-200 px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-700 shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-print"></i> Cetak Laporan
                    </a>
                @endif
                <a href="{{ route('stock.in') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-arrow-down"></i> Catat Masuk
                </a>
                <a href="{{ route('stock.out') }}" class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-amber-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-arrow-up"></i> Catat Keluar
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Produk -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Jenis Produk</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalProducts) }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-microchip text-blue-500"></i> Katalog Elektronik
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Valuasi Inventaris (Khusus Admin) atau Total Transaksi (Staff) -->
        @if($isAdmin)
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Valuasi Total Aset</p>
                        <h3 class="text-xl sm:text-2xl font-black text-indigo-900 mt-1">Rp {{ number_format($totalValuation, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-indigo-600 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-sack-dollar"></i> Nilai Total Stok Fisik
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Mutasi Transaksi</p>
                        <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalTransactions) }}</h3>
                        <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-receipt text-indigo-500"></i> Log Aktivitas Gudang
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
            </div>
        @endif

        <!-- Card 3: Peringatan Stok Kritis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Stok Kritis / Menipis</p>
                    <h3 class="text-2xl font-black text-rose-600 mt-1">{{ number_format($lowStock) }}</h3>
                    <p class="text-[11px] text-rose-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> Perlu Segera Restock
                    </p>
                </div>
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Volume Masuk vs Keluar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Volume Arus Barang</p>
                    <div class="flex items-center gap-3 mt-1">
                        <div>
                            <span class="text-xs text-indigo-600 font-bold block">IN</span>
                            <span class="text-base font-black text-slate-800">{{ number_format($totalIn) }}</span>
                        </div>
                        <div class="border-l border-slate-200 pl-3">
                            <span class="text-xs text-amber-600 font-bold block">OUT</span>
                            <span class="text-base font-black text-slate-800">{{ number_format($totalOut) }}</span>
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Critical Products Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Diagram Arus Barang 7 Hari -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Arus Pergerakan Barang (7 Hari Terakhir)</h3>
                    <p class="text-xs text-slate-400">Perbandingan kuantitas barang masuk (IN) dan keluar (OUT) per hari</p>
                </div>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">Real-Time</span>
            </div>
            <div class="relative h-64">
                <canvas id="movementChart"></canvas>
            </div>
        </div>

        <!-- Produk Stok Kritis -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Perlu Pengadaan (Restock)</h3>
                        <p class="text-xs text-slate-400">Stok fisik &le; batas minimum</p>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($criticalProducts as $prod)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $prod->name }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $prod->sku }} &bull; {{ $prod->category->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-extrabold text-rose-600 block">{{ $prod->stock }} Unit</span>
                                <span class="text-[10px] text-slate-400">Min: {{ $prod->minimum_stock }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-2 block"></i>
                            Semua stok produk saat ini dalam batas aman!
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="mt-4 text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center justify-center gap-1 transition">
                Lihat Semua Stok Menipis <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>

    <!-- Bottom Row: Recent Movements & Admin Category Breakdown -->
    <div class="grid grid-cols-1 {{ $isAdmin ? 'lg:grid-cols-2' : '' }} gap-6">
        <!-- Recent Movements -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Aktivitas Mutasi Terkini</h3>
                    <p class="text-xs text-slate-400">5 transaksi mutasi stok fisik terbaru</p>
                </div>
                <a href="{{ route('stock.history') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Semua <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase">
                            <th class="pb-2">Waktu</th>
                            <th class="pb-2">Produk</th>
                            <th class="pb-2 text-center">Tipe</th>
                            <th class="pb-2 text-right">Qty</th>
                            <th class="pb-2">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentMovements as $mv)
                            <tr>
                                <td class="py-2.5 text-slate-400">{{ $mv->created_at->format('d/m H:i') }}</td>
                                <td class="py-2.5 font-bold text-slate-800">{{ $mv->product->name ?? 'Produk' }}</td>
                                <td class="py-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded font-extrabold text-[10px] {{ $mv->type === 'IN' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $mv->type }}
                                    </span>
                                </td>
                                <td class="py-2.5 text-right font-black {{ $mv->type === 'IN' ? 'text-indigo-600' : 'text-amber-600' }}">
                                    {{ $mv->type === 'IN' ? '+' : '-' }}{{ $mv->quantity }}
                                </td>
                                <td class="py-2.5 text-slate-500">{{ $mv->user->name ?? 'Staff' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">Belum ada mutasi stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Admin Category Valuation Breakdown -->
        @if($isAdmin)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Ringkasan per Kategori (Admin Only)</h3>
                        <p class="text-xs text-slate-400">Valuasi dan kuantitas aset gudang per kategori produk</p>
                    </div>
                    <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-2.5 py-1 rounded-lg">5 Kategori</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase">
                                <th class="pb-2">Kategori</th>
                                <th class="pb-2 text-center">Varian</th>
                                <th class="pb-2 text-center">Total Fisik</th>
                                <th class="pb-2 text-right">Nilai Aset (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($categorySummary as $cat)
                                <tr>
                                    <td class="py-2.5 font-bold text-slate-800">{{ $cat['name'] }}</td>
                                    <td class="py-2.5 text-center text-slate-600">{{ $cat['total_items'] }}</td>
                                    <td class="py-2.5 text-center font-semibold text-slate-700">{{ $cat['total_stock'] }} Unit</td>
                                    <td class="py-2.5 text-right font-black text-indigo-900">Rp {{ number_format($cat['total_valuation'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('movementChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Barang Masuk (IN)',
                    data: {!! json_encode($chartInData) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.85)',
                    borderRadius: 8,
                },
                {
                    label: 'Barang Keluar (OUT)',
                    data: {!! json_encode($chartOutData) !!},
                    backgroundColor: 'rgba(217, 119, 6, 0.85)',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { family: 'Plus Jakarta Sans', size: 11 } } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
