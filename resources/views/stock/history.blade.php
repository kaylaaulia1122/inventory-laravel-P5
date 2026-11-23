@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Mutasi Stok (Audit Trail)</h2>
            <p class="text-xs text-slate-500 mt-1">Log rekam jejak lengkap barang masuk dan barang keluar secara kronologis</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('stock.print', request()->query()) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('stock.history') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <!-- Search -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari Nama / SKU..." class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50">
            </div>

            <!-- Range Filter -->
            <div>
                <select name="range" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all" {{ $range === 'all' ? 'selected' : '' }}>Semua Periode</option>
                    <option value="daily" {{ $range === 'daily' ? 'selected' : '' }}>Hari Ini (Daily)</option>
                    <option value="weekly" {{ $range === 'weekly' ? 'selected' : '' }}>7 Hari Terakhir (Weekly)</option>
                    <option value="monthly" {{ $range === 'monthly' ? 'selected' : '' }}>30 Hari Terakhir (Monthly)</option>
                    <option value="yearly" {{ $range === 'yearly' ? 'selected' : '' }}>1 Tahun Terakhir (Yearly)</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select name="type" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" {{ $type === '' ? 'selected' : '' }}>Semua Tipe Mutasi</option>
                    <option value="IN" {{ $type === 'IN' ? 'selected' : '' }}>Barang Masuk (IN)</option>
                    <option value="OUT" {{ $type === 'OUT' ? 'selected' : '' }}>Barang Keluar (OUT)</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-xs"></i> Terapkan
                </button>
                @if($q !== '' || $range !== 'all' || $type !== '')
                    <a href="{{ route('stock.history') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2.5 rounded-xl text-xs font-semibold transition">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Waktu Transaksi</th>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5 text-center">Tipe</th>
                        <th class="px-5 py-3.5 text-right">Qty</th>
                        <th class="px-5 py-3.5">Petugas</th>
                        <th class="px-5 py-3.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($movements as $mv)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 text-slate-500 font-medium">
                                {{ $mv->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 block">{{ $mv->product->name ?? 'Produk Dihapus' }}</span>
                                <span class="font-mono text-[11px] text-slate-400">{{ $mv->product->sku ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full font-black text-[10px] {{ $mv->type === 'IN' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $mv->type === 'IN' ? 'BARANG MASUK' : 'BARANG KELUAR' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-black {{ $mv->type === 'IN' ? 'text-indigo-600' : 'text-amber-600' }}">
                                {{ $mv->type === 'IN' ? '+' : '-' }}{{ $mv->quantity }} Unit
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-semibold">
                                {{ $mv->user->name ?? 'Staff Gudang' }}
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">
                                {{ $mv->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-clock-rotate-left text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada data mutasi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection
