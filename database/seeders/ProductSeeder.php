<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // Import the Product model

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop Gaming XYZ',
            'description' => 'Powerful gaming laptop with latest GPU and CPU.',
            'price' => 1500.00,
            'category' => 'Electronics',
            'unit' => 'unit',
            'brand' => 'TechPro',
            'barcode' => '1234567890123',
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'T-Shirt Katun Premium',
            'description' => 'Comfortable 100% premium cotton t-shirt.',
            'price' => 25.50,
            'category' => 'Apparel',
            'unit' => 'piece',
            'brand' => 'FashionWear',
            'barcode' => '9876543210987',
            'stock' => 200,
        ]);

        Product::create([
            'name' => 'Organic Coffee Beans',
            'description' => 'Freshly roasted organic Arabica coffee beans.',
            'price' => 12.99,
            'category' => 'Food & Beverage',
            'unit' => 'kg',
            'brand' => 'BeanMaster',
            'barcode' => '1122334455667',
            'stock' => 150,
        ]);

        // You can add more products here
    }
}