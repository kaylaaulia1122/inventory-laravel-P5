<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use DatabaseMigrations;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();
        $this->actingAs($this->admin);
    }

    public function test_dashboard_can_be_accessed(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee('INVENTORYPRO');
    }

    public function test_product_catalog_listing_and_filter(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Master Data Produk');

        // Test search
        $responseSearch = $this->get('/products?q=Galaxy');
        $responseSearch->assertStatus(200);

        // Test sorting
        $responseSort = $this->get('/products?sort=price');
        $responseSort->assertStatus(200);
    }

    public function test_create_and_store_product(): void
    {
        $category = Category::first();

        $response = $this->post('/products', [
            'sku' => 'SKU-TEST-999',
            'name' => 'Produk Uji Coba Unit Test',
            'category_id' => $category->id,
            'price' => 1500000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-TEST-999',
            'name' => 'Produk Uji Coba Unit Test',
        ]);
    }

    public function test_stock_in_increases_stock_and_logs_movement(): void
    {
        $product = Product::first();
        $initialStock = $product->stock;
        $qty = 7;

        $response = $this->post('/stock/in', [
            'product_id' => $product->id,
            'quantity' => $qty,
            'notes' => 'Testing Stock In',
        ]);

        $response->assertRedirect('/stock/history');

        $product->refresh();
        $this->assertEquals($initialStock + $qty, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'IN',
            'quantity' => $qty,
        ]);
    }

    public function test_stock_out_decreases_stock_and_logs_movement(): void
    {
        $product = Product::first();
        $product->update(['stock' => 20]);

        $response = $this->post('/stock/out', [
            'product_id' => $product->id,
            'quantity' => 5,
            'notes' => 'Testing Stock Out',
        ]);

        $response->assertRedirect('/stock/history');

        $product->refresh();
        $this->assertEquals(15, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'OUT',
            'quantity' => 5,
        ]);
    }

    public function test_stock_out_fails_when_quantity_exceeds_available_stock(): void
    {
        $product = Product::first();
        $product->update(['stock' => 3]);

        // Attempt to take out 10 when stock is only 3
        $response = $this->from('/stock/out')->post('/stock/out', [
            'product_id' => $product->id,
            'quantity' => 10,
            'notes' => 'Testing Overdraft',
        ]);

        $response->assertRedirect('/stock/out');
        $response->assertSessionHas('error');

        $product->refresh();
        $this->assertEquals(3, $product->stock); // Unchanged!
    }

    public function test_eloquent_relationships(): void
    {
        $category = Category::first();
        $this->assertNotNull($category->products);

        $product = Product::first();
        $this->assertNotNull($product->category);
        $this->assertInstanceOf(Category::class, $product->category);

        $this->assertNotNull($product->stockMovements);
    }
}
