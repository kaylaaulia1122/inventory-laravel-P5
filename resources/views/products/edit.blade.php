@extends('layouts.app')

@section('title', 'Ubah Produk')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog Produk
        </a>
        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200/50">Edit Mode</span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-pen"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Ubah Data Produk</h2>
                <p class="text-xs text-slate-400">Perbarui rincian informasi dan batas stok produk {{ $product->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- SKU & Name -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Kode SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('sku') border-rose-500 @enderror">
                    @error('sku')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Category & Supplier -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Kategori *</label>
                    <select name="category_id" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('category_id') border-rose-500 @enderror">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Pemasok Utama</label>
                    <select name="supplier_id" class="w-full border border-slate-200 p-2.5 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Tanpa Pemasok --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id', $product->suppliers->first()?->id) == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Price, Stock, Minimum Stock -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Harga Satuan (Rp) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" min="0" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('price') border-rose-500 @enderror">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Stok Fisik *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('stock') border-rose-500 @enderror">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-600 mb-1.5">Batas Minimum *</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0" required class="w-full border border-slate-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('minimum_stock') border-rose-500 @enderror">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Perbarui Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
