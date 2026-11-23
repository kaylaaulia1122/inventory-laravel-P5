<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user ? $user->isAdmin() : true;

        // 1. Agregat Utama
        $totalProducts = Product::count();
        $lowStock = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalIn = StockMovement::where('type', 'IN')->sum('quantity');
        $totalOut = StockMovement::where('type', 'OUT')->sum('quantity');
        $totalTransactions = StockMovement::count();

        // Valuasi Total (Khusus Admin)
        $totalValuation = 0;
        if ($isAdmin) {
            $totalValuation = Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0;
        }

        // 2. Data Grafik Arus Barang 7 Hari Terakhir
        $chartDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDays[$d] = [
                'label' => Carbon::now()->subDays($i)->format('d M'),
                'in' => 0,
                'out' => 0,
            ];
        }

        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $movements = StockMovement::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as m_date, type, SUM(quantity) as total_qty')
            ->groupBy('m_date', 'type')
            ->get();

        foreach ($movements as $m) {
            $d = $m->m_date;
            if (isset($chartDays[$d])) {
                if ($m->type === 'IN') {
                    $chartDays[$d]['in'] = (int)$m->total_qty;
                } else {
                    $chartDays[$d]['out'] = (int)$m->total_qty;
                }
            }
        }

        $chartLabels = array_column(array_values($chartDays), 'label');
        $chartInData = array_column(array_values($chartDays), 'in');
        $chartOutData = array_column(array_values($chartDays), 'out');

        // 3. Mutasi Terkini (5 Transaksi Terakhir)
        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Produk Stok Kritis
        $criticalProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // 5. Ringkasan per Kategori (Khusus Admin)
        $categorySummary = [];
        if ($isAdmin) {
            $categorySummary = Category::withCount('products')
                ->with(['products' => function ($q) {
                    $q->select('id', 'category_id', 'stock', 'price');
                }])
                ->get()
                ->map(function ($cat) {
                    $totalStock = $cat->products->sum('stock');
                    $totalVal = $cat->products->sum(function ($p) {
                        return $p->stock * $p->price;
                    });
                    return [
                        'name' => $cat->name,
                        'total_items' => $cat->products_count,
                        'total_stock' => $totalStock,
                        'total_valuation' => $totalVal,
                    ];
                });
        }

        return view('dashboard', compact(
            'user',
            'isAdmin',
            'totalProducts',
            'lowStock',
            'totalIn',
            'totalOut',
            'totalTransactions',
            'totalValuation',
            'chartLabels',
            'chartInData',
            'chartOutData',
            'recentMovements',
            'criticalProducts',
            'categorySummary'
        ));
    }
}
