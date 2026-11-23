<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $categoryId = $request->get('category_id');
        $stockStatus = trim($request->get('stock_status', ''));
        $sortKey = $request->get('sort', 'name');

        // Whitelist sorting
        $allowedSorts = [
            'name' => 'name',
            'price' => 'price',
            'stock' => 'stock',
            'sku' => 'sku',
        ];
        $sortColumn = $allowedSorts[$sortKey] ?? 'name';

        $products = Product::with(['category', 'suppliers'])
            ->when($q, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $catId) {
                $query->where('category_id', $catId);
            })
            ->when($stockStatus === 'low', function ($query) {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            })
            ->when($stockStatus === 'empty', function ($query) {
                $query->where('stock', 0);
            })
            ->when($stockStatus === 'safe', function ($query) {
                $query->whereColumn('stock', '>', 'minimum_stock');
            })
            ->orderBy($sortColumn, 'asc')
            ->paginate(8)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'q', 'categoryId', 'stockStatus', 'sortKey'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ]);

        $product = Product::create($validated);

        if (!empty($validated['supplier_id'])) {
            $product->suppliers()->attach($validated['supplier_id'], [
                'supplier_price' => $product->price * 0.85
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', "Produk {$product->name} ({$product->sku}) berhasil ditambahkan ke inventaris!");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements.user' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ]);

        $product->update($validated);

        if (isset($validated['supplier_id'])) {
            if ($validated['supplier_id']) {
                $product->suppliers()->sync([$validated['supplier_id'] => ['supplier_price' => $product->price * 0.85]]);
            } else {
                $product->suppliers()->detach();
            }
        }

        return redirect()->route('products.index')
            ->with('success', "Data produk {$product->name} berhasil diperbarui.");
    }

    public function destroy(Product $product)
    {
        if ($product->stockMovements()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', "Gagal menghapus produk {$product->name}! Produk memiliki riwayat mutasi stok (Audit Trail).");
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produk {$name} berhasil dihapus dari inventaris.");
    }
}
