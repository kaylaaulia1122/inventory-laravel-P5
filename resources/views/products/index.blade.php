@extends('layouts.app')

@section('title', 'Master Produk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Master Data Produk</h2>
            <p class="text-xs text-slate-500 mt-1">Katalog persediaan produk elektronik toko dan status stok fisik</p>
        </div>
        <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-blue-500/25 transition flex items-center gap-2 w-fit">
            <i class="fa-solid fa-plus"></i> Tambah Produk Baru
        </a>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search Input -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari Nama / Kode SKU..." class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50 focus:bg-white transition">
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kategori ({{ $categories->count() }})</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stock Status Filter -->
            <div>
                <select name="stock_status" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" {{ $stockStatus === '' ? 'selected' : '' }}>Semua Status Stok</option>
                    <option value="low" {{ $stockStatus === 'low' ? 'selected' : '' }}>⚠️ Stok Kritis (&le; Minimum)</option>
                    <option value="empty" {{ $stockStatus === 'empty' ? 'selected' : '' }}>❌ Stok Habis (0 Unit)</option>
                    <option value="safe" {{ $stockStatus === 'safe' ? 'selected' : '' }}>✅ Stok Aman (> Minimum)</option>
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-filter text-xs"></i> Filter Data
                </button>
                @if($q !== '' || $categoryId || $stockStatus !== '')
                    <a href="{{ route('products.index') }}" title="Reset Filter" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Sorting Whitelist Buttons -->
        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-1.5 text-slate-500">
                <span class="font-bold text-slate-400">Urutkan:</span>
                @foreach(['name' => 'Nama', 'price' => 'Harga', 'stock' => 'Stok', 'sku' => 'SKU'] as $col => $label)
                    <a href="{{ request()->fullUrlWithQuery(['sort' => $col]) }}" class="px-2.5 py-1 rounded-lg transition {{ $sortKey === $col ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <div class="text-slate-400 text-[11px]">
                Menampilkan <b>{{ $products->firstItem() ?? 0 }}</b> - <b>{{ $products->lastItem() ?? 0 }}</b> dari <b>{{ $products->total() }}</b> produk
            </div>
        </div>
    </div>

    <!-- Product Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">SKU & Produk</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-right">Harga Satuan</th>
                        <th class="px-5 py-3.5 text-center">Stok Fisik</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4">
                                <a href="{{ route('products.show', $product) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                    {{ $product->name }}
                                </a>
                                <span class="font-mono text-[11px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">
                                    {{ $product->sku }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $product->category->name }}
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-slate-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-black text-slate-900">{{ $product->stock }}</span>
                                <span class="text-[10px] text-slate-400 block">Min: {{ $product->minimum_stock }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <x-badge :status="$product->stock_status" />
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('products.show', $product) }}" title="Detail" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" title="Edit" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300 block"></i>
                                Tidak ada produk yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination with Query String -->
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
