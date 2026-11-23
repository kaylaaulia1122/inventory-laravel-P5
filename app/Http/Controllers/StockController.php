<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockController extends Controller
{
    public function createIn()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('stock.in', compact('products'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
                $product->increment('stock', $validated['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id() ?? 1,
                    'type' => 'IN',
                    'quantity' => $validated['quantity'],
                    'notes' => $validated['notes'] ?? 'Penerimaan barang masuk',
                ]);
            });

            return redirect()->route('stock.history')
                ->with('success', 'Transaksi Barang Masuk berhasil dicatat! Stok produk bertambah.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function createOut()
    {
        $products = Product::with('category')->orderBy('name')->get();
        return view('stock.out', compact('products'));
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                if ($product->stock < $validated['quantity']) {
                    throw new \Exception("Stok tidak mencukupi! Stok fisik saat ini hanya tersisa {$product->stock} unit.");
                }

                $product->decrement('stock', $validated['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id() ?? 1,
                    'type' => 'OUT',
                    'quantity' => $validated['quantity'],
                    'notes' => $validated['notes'] ?? 'Pengeluaran barang',
                ]);
            });

            return redirect()->route('stock.history')
                ->with('success', 'Transaksi Barang Keluar berhasil dicatat! Stok fisik telah disesuaikan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $range = $request->get('range', 'all');
        $type = $request->get('type', '');
        $q = trim($request->get('q', ''));

        $query = StockMovement::with(['product.category', 'user']);

        if ($range === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($range === 'weekly') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($range === 'monthly') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($range === 'yearly') {
            $query->where('created_at', '>=', Carbon::now()->subDays(365));
        }

        if (in_array($type, ['IN', 'OUT'])) {
            $query->where('type', $type);
        }

        if ($q !== '') {
            $query->whereHas('product', function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $movements = $query->latest()->paginate(10)->withQueryString();

        return view('stock.history', compact('movements', 'range', 'type', 'q'));
    }

    public function printReport(Request $request)
    {
        $range = $request->get('range', 'all');
        $type = $request->get('type', '');

        $query = StockMovement::with(['product.category', 'user']);

        if ($range === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($range === 'weekly') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($range === 'monthly') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        if (in_array($type, ['IN', 'OUT'])) {
            $query->where('type', $type);
        }

        $movements = $query->latest()->get();
        $products = Product::with('category')->orderBy('name')->get();
        $totalValuation = $products->sum(fn ($p) => $p->stock * $p->price);

        return view('stock.print', compact('movements', 'products', 'totalValuation', 'range', 'type'));
    }
}
