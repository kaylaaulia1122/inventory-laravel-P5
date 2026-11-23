@extends('layouts.app')

@section('title', 'Pencatatan Barang Keluar')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200/50">Form Pengeluaran Barang</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-arrow-up"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Pencatatan Barang Keluar</h2>
                <p class="text-xs text-slate-400">Pengurangan stok fisik untuk penjualan atau pengiriman (ACID Protected)</p>
            </div>
        </div>

        <form method="POST" action="{{ route('stock.out.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Pilih Produk Elektronik *</label>
                <select name="product_id" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->sku }}) &bull; Stok Tersedia: {{ $p->stock }} Unit
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Jumlah Kuantitas Keluar *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-shield-halved text-amber-500"></i> Sistem akan memvalidasi agar stok tidak bernilai negatif.
                </p>
                @error('quantity')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Catatan / Alasan Pengeluaran</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Pengiriman pesanan toko cabang, penjualan langsung..." class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-amber-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-arrow-up"></i> Catat Barang Keluar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
