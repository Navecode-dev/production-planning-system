<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store; // Import the Store model

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name' => 'Toko Jaya Abadi',
            'address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'sales_area' => 'Jakarta',
            'contact' => '021-12345678',
            'person_in_charge' => 'Budi Santoso',
        ]);

        Store::create([
            'name' => 'Warung Sejahtera',
            'address' => 'Jl. Raya Bogor Km. 25, Depok',
            'sales_area' => 'Depok',
            'contact' => '021-87654321',
            'person_in_charge' => 'Siti Aminah',
        ]);

        Store::create([
            'name' => 'Grosir Maju Bersama',
            'address' => 'Jl. Pahlawan No. 5, Bandung',
            'sales_area' => 'Bandung',
            'contact' => '022-98765432',
            'person_in_charge' => 'Agus Wijaya',
        ]);

        // Tambahkan lebih banyak data toko di sini jika diperlukan
    }
}
