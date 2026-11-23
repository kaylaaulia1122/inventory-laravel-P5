@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog Produk
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-1.5 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Product Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $product->sku }}</span>
                <h1 class="text-2xl font-black text-slate-900 mt-2">{{ $product->name }}</h1>
                <p class="text-xs text-slate-400 mt-1">Kategori: <span class="font-semibold text-slate-700">{{ $product->category->name }}</span></p>
            </div>
            <div>
                <x-badge :status="$product->stock_status" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 py-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Harga Satuan</span>
                <p class="text-xl font-black text-slate-900 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Stok Fisik Saat Ini</span>
                <p class="text-xl font-black {{ $product->stock <= $product->minimum_stock ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                    {{ $product->stock }} Unit
                </p>
                <span class="text-[11px] text-slate-400">Batas Minimum: {{ $product->minimum_stock }} Unit</span>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Total Valuasi Stok</span>
                <p class="text-xl font-black text-indigo-900 mt-1">
                    Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Supplier & Movements -->
        <div class="pt-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-2">Pemasok Terdaftar</h3>
                @forelse($product->suppliers as $sup)
                    <div class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl text-xs">
                        <i class="fa-solid fa-truck text-indigo-600"></i>
                        <span class="font-bold text-slate-800">{{ $sup->name }}</span>
                        <span class="text-slate-400">&bull; {{ $sup->phone }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Belum ada pemasok yang terhubung.</p>
                @endforelse
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-2">Riwayat Mutasi Produk Ini</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 font-bold uppercase border-b border-slate-100">
                                <th class="pb-2">Waktu</th>
                                <th class="pb-2 text-center">Tipe</th>
                                <th class="pb-2 text-right">Qty</th>
                                <th class="pb-2">Petugas</th>
                                <th class="pb-2">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($product->stockMovements as $sm)
                                <tr>
                                    <td class="py-2.5 text-slate-400">{{ $sm->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-2.5 text-center">
                                        <span class="px-2 py-0.5 rounded font-extrabold text-[10px] {{ $sm->type === 'IN' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ $sm->type }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 text-right font-black {{ $sm->type === 'IN' ? 'text-indigo-600' : 'text-amber-600' }}">
                                        {{ $sm->type === 'IN' ? '+' : '-' }}{{ $sm->quantity }}
                                    </td>
                                    <td class="py-2.5 text-slate-600">{{ $sm->user->name ?? 'Staff' }}</td>
                                    <td class="py-2.5 text-slate-500">{{ $sm->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-400">Belum ada mutasi stok untuk produk ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
