<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin dan Staff bawaan P4
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Utama',
                'email' => 'admin@inventorypro.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Gudang',
                'email' => 'staff@inventorypro.test',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]
        );

        // 2. 5 Kategori Master P4
        $categoriesData = [
            'Kebutuhan Rumah Tangga',
            'Kantor',
            'Hiburan & Multimedia',
            'Pribadi & Gadget',
            'Keamanan & Bangunan',
        ];

        $categories = [];
        foreach ($categoriesData as $catName) {
            $categories[$catName] = Category::firstOrCreate(['name' => $catName]);
        }

        // 3. Data Pemasok (Suppliers)
        $suppliersData = [
            ['name' => 'PT Sinar Elektronik Jaya', 'phone' => '081234567890'],
            ['name' => 'CV Sumber Makmur Abadi', 'phone' => '082198765432'],
            ['name' => 'PT Mitra Logistik Prima', 'phone' => '085311223344'],
            ['name' => 'CV Multi Komponen Global', 'phone' => '087855667788'],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sData) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sData['name']], $sData);
        }

        // 4. Data Produk Elektronik
        $productsData = [
            [
                'category_id' => $categories['Pribadi & Gadget']->id,
                'sku' => 'SKU-GDG-001',
                'name' => 'Smartphone Galaxy A54 5G',
                'price' => 5499000.00,
                'stock' => 18,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['Pribadi & Gadget']->id,
                'sku' => 'SKU-GDG-002',
                'name' => 'Smartwatch Active Pro',
                'price' => 1250000.00,
                'stock' => 4, // Stok Rendah
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['Kantor']->id,
                'sku' => 'SKU-OFC-001',
                'name' => 'Printer Laser Mono Pro M404n',
                'price' => 3850000.00,
                'stock' => 12,
                'minimum_stock' => 3,
            ],
            [
                'category_id' => $categories['Kantor']->id,
                'sku' => 'SKU-OFC-002',
                'name' => 'Kertas HVS A4 80gsm (Dus/5 Rim)',
                'price' => 265000.00,
                'stock' => 0, // Stok Habis
                'minimum_stock' => 10,
            ],
            [
                'category_id' => $categories['Hiburan & Multimedia']->id,
                'sku' => 'SKU-ENT-001',
                'name' => 'Smart LED TV 50 Inch 4K UHD',
                'price' => 6199000.00,
                'stock' => 8,
                'minimum_stock' => 4,
            ],
            [
                'category_id' => $categories['Hiburan & Multimedia']->id,
                'sku' => 'SKU-ENT-002',
                'name' => 'Wireless Soundbar with Subwoofer',
                'price' => 1799000.00,
                'stock' => 3, // Stok Rendah
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['Kebutuhan Rumah Tangga']->id,
                'sku' => 'SKU-HSE-001',
                'name' => 'Air Purifier HEPA Filter H13',
                'price' => 1890000.00,
                'stock' => 15,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['Kebutuhan Rumah Tangga']->id,
                'sku' => 'SKU-HSE-002',
                'name' => 'Vacuum Cleaner Cordless Cyclone',
                'price' => 2150000.00,
                'stock' => 2, // Stok Rendah
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['Keamanan & Bangunan']->id,
                'sku' => 'SKU-SEC-001',
                'name' => 'CCTV IP Camera Wi-Fi Outdoor 360',
                'price' => 475000.00,
                'stock' => 25,
                'minimum_stock' => 6,
            ],
            [
                'category_id' => $categories['Keamanan & Bangunan']->id,
                'sku' => 'SKU-SEC-002',
                'name' => 'Smart Door Lock Biometric Fingerprint',
                'price' => 1650000.00,
                'stock' => 0, // Stok Habis
                'minimum_stock' => 4,
            ],
        ];

        foreach ($productsData as $pData) {
            $product = Product::firstOrCreate(['sku' => $pData['sku']], $pData);
            if (!empty($suppliers)) {
                $product->suppliers()->syncWithoutDetaching([
                    $suppliers[array_rand($suppliers)]->id => [
                        'supplier_price' => $product->price * 0.85,
                    ]
                ]);
            }
        }

        // 5. Riwayat Mutasi Awal (Stock Movement History)
        $allProducts = Product::all();
        foreach ($allProducts as $prod) {
            if ($prod->stock > 0) {
                StockMovement::create([
                    'product_id' => $prod->id,
                    'user_id' => $admin->id,
                    'type' => 'IN',
                    'quantity' => $prod->stock + 5,
                    'notes' => 'Penerimaan batch awal gudang pusat',
                    'created_at' => Carbon::now()->subDays(rand(2, 6)),
                ]);

                StockMovement::create([
                    'product_id' => $prod->id,
                    'user_id' => $staff->id,
                    'type' => 'OUT',
                    'quantity' => 5,
                    'notes' => 'Pengiriman reguler ke toko cabang',
                    'created_at' => Carbon::now()->subDays(rand(0, 2)),
                ]);
            }
        }
    }
}