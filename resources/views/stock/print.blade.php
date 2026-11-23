<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi Persediaan Barang - INVENTORYPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 md:p-10 font-sans text-slate-800">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200 print:border-none print:shadow-none print:p-0">
        <!-- Print Action Bar -->
        <div class="no-print flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
            <span class="text-xs text-slate-500">Pratinjau Cetak Laporan Resmi</span>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Dokumen
                </button>
                <button onclick="window.close()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs font-semibold">Tutup</button>
            </div>
        </div>

        <!-- Official Header -->
        <div class="text-center pb-6 border-b-2 border-slate-900 mb-6">
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900">INVENTORYPRO MANAGEMENT</h1>
            <p class="text-xs text-slate-600 mt-1">Laporan Resmi Status Persediaan & Mutasi Fisik Gudang Elektronik</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] uppercase font-bold text-slate-400">Total Varian Produk</span>
                <p class="text-lg font-black text-slate-900">{{ $products->count() }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] uppercase font-bold text-slate-400">Total Unit Fisik</span>
                <p class="text-lg font-black text-slate-900">{{ $products->sum('stock') }} Unit</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                <span class="text-[10px] uppercase font-bold text-slate-400">Total Valuasi Aset</span>
                <p class="text-lg font-black text-indigo-900">Rp {{ number_format($totalValuation, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Product Table -->
        <div class="mb-8">
            <h3 class="text-xs font-black uppercase text-slate-800 mb-2">1. Rekapitulasi Persediaan Produk</h3>
            <table class="w-full text-left text-xs border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-bold uppercase">
                    <tr>
                        <th class="p-2 border border-slate-300">No</th>
                        <th class="p-2 border border-slate-300">SKU</th>
                        <th class="p-2 border border-slate-300">Nama Produk</th>
                        <th class="p-2 border border-slate-300">Kategori</th>
                        <th class="p-2 border border-slate-300 text-right">Harga</th>
                        <th class="p-2 border border-slate-300 text-center">Stok</th>
                        <th class="p-2 border border-slate-300 text-right">Valuasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $idx => $p)
                        <tr>
                            <td class="p-2 border border-slate-300 text-center">{{ $idx + 1 }}</td>
                            <td class="p-2 border border-slate-300 font-mono">{{ $p->sku }}</td>
                            <td class="p-2 border border-slate-300 font-semibold">{{ $p->name }}</td>
                            <td class="p-2 border border-slate-300">{{ $p->category->name }}</td>
                            <td class="p-2 border border-slate-300 text-right">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="p-2 border border-slate-300 text-center font-bold">{{ $p->stock }}</td>
                            <td class="p-2 border border-slate-300 text-right font-bold">Rp {{ number_format($p->stock * $p->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-200 text-xs text-center">
            <div>
                <p class="text-slate-500">Dibuat Oleh,</p>
                <div class="h-16"></div>
                <p class="font-bold underline text-slate-800">Staff Gudang</p>
                <p class="text-[10px] text-slate-400">Divisi Operasional</p>
            </div>
            <div>
                <p class="text-slate-500">Mengetahui & Menyetujui,</p>
                <div class="h-16"></div>
                <p class="font-bold underline text-slate-800">Administrator Utama</p>
                <p class="text-[10px] text-slate-400">Kepala Gudang & Inventaris</p>
            </div>
        </div>
    </div>
</body>
</html>
