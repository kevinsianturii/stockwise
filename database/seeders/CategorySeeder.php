<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan & Minuman', 'description' => 'Produk makanan dan minuman konsumsi'],
            ['name' => 'Peralatan Rumah Tangga', 'description' => 'Peralatan untuk keperluan rumah tangga sehari-hari'],
            ['name' => 'Elektronik', 'description' => 'Perangkat elektronik dan aksesoris'],
            ['name' => 'Alat Tulis & Kantor', 'description' => 'Perlengkapan tulis menulis dan kantor'],
            ['name' => 'Pakaian & Tekstil', 'description' => 'Pakaian, kain, dan aksesori fashion'],
            ['name' => 'Kesehatan & Kecantikan', 'description' => 'Produk kesehatan, vitamin, dan kecantikan'],
            ['name' => 'Bahan Bangunan', 'description' => 'Material dan perlengkapan bangunan'],
            ['name' => 'Lain-lain', 'description' => 'Produk yang tidak termasuk kategori lain'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
