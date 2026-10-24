<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'PT Sinar Maju Jaya',
                'contact_person' => 'Budi Santoso',
                'phone'          => '021-5551234',
                'email'          => 'budi@sinarmaju.co.id',
                'address'        => 'Jl. Raya Industri No. 12, Jakarta Utara',
            ],
            [
                'name'           => 'CV Berkah Bersama',
                'contact_person' => 'Dewi Rahayu',
                'phone'          => '0812-3456789',
                'email'          => 'dewi@berkahbersama.com',
                'address'        => 'Jl. Pahlawan No. 45, Surabaya',
            ],
            [
                'name'           => 'UD Mandiri Sejahtera',
                'contact_person' => 'Ahmad Fauzi',
                'phone'          => '022-7654321',
                'email'          => 'ahmad@mandirisjahtera.co.id',
                'address'        => 'Jl. Sudirman No. 88, Bandung',
            ],
            [
                'name'           => 'Toko Grosir Nusantara',
                'contact_person' => 'Sri Mulyani',
                'phone'          => '0274-654321',
                'email'          => null,
                'address'        => 'Pasar Besar Blok C No. 23, Yogyakarta',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }
    }
}
