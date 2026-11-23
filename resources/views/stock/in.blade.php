@extends('layouts.app')

@section('title', 'Pencatatan Barang Masuk')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-200/50">Form Penerimaan Barang</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-arrow-down"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Pencatatan Barang Masuk</h2>
                <p class="text-xs text-slate-400">Tambah stok fisik produk ke gudang secara otomatis</p>
            </div>
        </div>

        <form method="POST" action="{{ route('stock.in.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Pilih Produk Elektronik *</label>
                <select name="product_id" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->sku }}) &bull; Stok Saat Ini: {{ $p->stock }} Unit
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Jumlah Kuantitas Masuk *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('quantity')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Catatan / Keterangan Penerimaan</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Penerimaan surat jalan No. SJ-887 dari pemasok..." class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-arrow-down"></i> Catat Barang Masuk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
