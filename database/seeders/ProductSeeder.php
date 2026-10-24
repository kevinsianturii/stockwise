<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('name', 'Makanan & Minuman')->first();
        $peralatan = Category::where('name', 'Peralatan Rumah Tangga')->first();
        $elektronik = Category::where('name', 'Elektronik')->first();
        $atk = Category::where('name', 'Alat Tulis & Kantor')->first();

        $supplier1 = Supplier::first();
        $supplier2 = Supplier::skip(1)->first();
        $supplier3 = Supplier::skip(2)->first();

        $products = [
            // Makanan & Minuman
            ['sku' => 'MKN-001', 'name' => 'Beras Premium 5kg', 'category_id' => $makanan?->id, 'supplier_id' => $supplier1?->id, 'purchase_price' => 65000, 'selling_price' => 72000, 'current_stock' => 50, 'minimum_stock' => 20, 'unit' => 'karung'],
            ['sku' => 'MKN-002', 'name' => 'Minyak Goreng 2L', 'category_id' => $makanan?->id, 'supplier_id' => $supplier1?->id, 'purchase_price' => 28000, 'selling_price' => 32000, 'current_stock' => 3, 'minimum_stock' => 15, 'unit' => 'botol'],
            ['sku' => 'MKN-003', 'name' => 'Gula Pasir 1kg', 'category_id' => $makanan?->id, 'supplier_id' => $supplier2?->id, 'purchase_price' => 14000, 'selling_price' => 16500, 'current_stock' => 30, 'minimum_stock' => 25, 'unit' => 'kg'],
            ['sku' => 'MKN-004', 'name' => 'Teh Celup Kotak', 'category_id' => $makanan?->id, 'supplier_id' => $supplier2?->id, 'purchase_price' => 9500, 'selling_price' => 12000, 'current_stock' => 0, 'minimum_stock' => 10, 'unit' => 'kotak'],
            ['sku' => 'MKN-005', 'name' => 'Kopi Sachet 20s', 'category_id' => $makanan?->id, 'supplier_id' => $supplier1?->id, 'purchase_price' => 22000, 'selling_price' => 27500, 'current_stock' => 45, 'minimum_stock' => 20, 'unit' => 'pack'],
            ['sku' => 'MKN-006', 'name' => 'Susu UHT 1L', 'category_id' => $makanan?->id, 'supplier_id' => $supplier3?->id, 'purchase_price' => 16500, 'selling_price' => 19000, 'current_stock' => 8, 'minimum_stock' => 15, 'unit' => 'kotak'],

            // Peralatan Rumah Tangga
            ['sku' => 'PRT-001', 'name' => 'Sabun Cuci Piring 500ml', 'category_id' => $peralatan?->id, 'supplier_id' => $supplier2?->id, 'purchase_price' => 7500, 'selling_price' => 9500, 'current_stock' => 25, 'minimum_stock' => 10, 'unit' => 'botol'],
            ['sku' => 'PRT-002', 'name' => 'Deterjen Bubuk 1kg', 'category_id' => $peralatan?->id, 'supplier_id' => $supplier2?->id, 'purchase_price' => 15000, 'selling_price' => 18000, 'current_stock' => 12, 'minimum_stock' => 15, 'unit' => 'kg'],
            ['sku' => 'PRT-003', 'name' => 'Sikat Gigi', 'category_id' => $peralatan?->id, 'supplier_id' => $supplier3?->id, 'purchase_price' => 5000, 'selling_price' => 7500, 'current_stock' => 20, 'minimum_stock' => 10, 'unit' => 'pcs'],

            // Elektronik
            ['sku' => 'ELK-001', 'name' => 'Baterai AA (2pcs)', 'category_id' => $elektronik?->id, 'supplier_id' => $supplier1?->id, 'purchase_price' => 12000, 'selling_price' => 16000, 'current_stock' => 15, 'minimum_stock' => 10, 'unit' => 'pack'],
            ['sku' => 'ELK-002', 'name' => 'Kabel USB Type-C 1m', 'category_id' => $elektronik?->id, 'supplier_id' => $supplier3?->id, 'purchase_price' => 18000, 'selling_price' => 25000, 'current_stock' => 0, 'minimum_stock' => 5, 'unit' => 'pcs'],

            // ATK
            ['sku' => 'ATK-001', 'name' => 'Pulpen Ballpoint', 'category_id' => $atk?->id, 'supplier_id' => $supplier3?->id, 'purchase_price' => 2500, 'selling_price' => 3500, 'current_stock' => 100, 'minimum_stock' => 50, 'unit' => 'pcs'],
            ['sku' => 'ATK-002', 'name' => 'Buku Tulis 40 Lembar', 'category_id' => $atk?->id, 'supplier_id' => $supplier3?->id, 'purchase_price' => 4500, 'selling_price' => 6000, 'current_stock' => 5, 'minimum_stock' => 20, 'unit' => 'buku'],
            ['sku' => 'ATK-003', 'name' => 'Kertas A4 (1 Rim)', 'category_id' => $atk?->id, 'supplier_id' => $supplier2?->id, 'purchase_price' => 45000, 'selling_price' => 55000, 'current_stock' => 8, 'minimum_stock' => 5, 'unit' => 'rim'],
        ];

        foreach ($products as $product) {
            if (!$product['category_id']) continue;

            $existing = Product::where('sku', $product['sku'])->first();
            if (!$existing) {
                Product::create($product);
            }
        }
    }
}
