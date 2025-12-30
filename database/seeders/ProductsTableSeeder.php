<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Map category names to IDs
        $categoryMap = Category::pluck('id', 'name');
        // Example: ['Shipping' => 1, 'Storage' => 2, ...]

        $products = [
            [
                'name' => "Heavy-Duty Shipping Boxes (Set of 10)",
                'category_id' => $categoryMap['Shipping'] ?? null,
                'price' => 34.99,
                'image' => 'images/products/GPS.jpg',
                'description' => "Durable corrugated boxes for secure shipping of various items.",
                'status' => 'active',
                'stock_quantity' => 48,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Bubble Wrap Roll (12\" x 50ft)",
                'category_id' => $categoryMap['Shipping'] ?? null,
                'price' => 18.50,
               'image' => 'images/products/GPS.jpg',
                'description' => "Protective bubble wrap for cushioning fragile items during transit.",
                'status' => 'active',
                'stock_quantity' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Standard Wooden Pallet",
                'category_id' => $categoryMap['Storage'] ?? null,
                'price' => 24.99,
                'image' => 'images/products/GPS.jpg',
                'description' => "Standard 48\" x 40\" wooden pallet for warehouse storage and shipping.",
                'status' => 'active',
                'stock_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Hand Pallet Truck",
                'category_id' => $categoryMap['Storage'] ?? null,
                'price' => 899.99,
                'image' => 'images/products/GPS.jpg',
                'description' => "Manual pallet jack for moving loaded pallets in warehouses.",
                'status' => 'active',
                'stock_quantity' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "GPS Tracking Device",
                'category_id' => $categoryMap['Technology'] ?? null,
                'price' => 149.99,
                'image' => 'images/products/GPS.jpg',
                'description' => "Real-time GPS tracker with long battery life for shipment monitoring.",
                'status' => 'active',
                'stock_quantity' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Fleet Management Software",
                'category_id' => $categoryMap['Technology'] ?? null,
                'price' => 299.99,
                'image' => 'images/products/GPS.jpg',
                'description' => "Software suite for managing logistics operations and vehicle fleets.",
                'status' => 'active',
                'stock_quantity' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Shipping Labels (Roll of 500)",
                'category_id' => $categoryMap['Shipping'] ?? null,
                'price' => 42.75,
                'image' => 'images/products/GPS.jpg',
                'description' => "Professional adhesive shipping labels for thermal printers.",
                'status' => 'active',
                'stock_quantity' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Warehouse Barcode Scanner",
                'category_id' => $categoryMap['Technology'] ?? null,
                'price' => 189.99,
                'image' => 'images/products/GPS.jpg',
                'description' => "Wireless barcode scanner for inventory management and tracking.",
                'status' => 'active',
                'stock_quantity' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            if ($product['category_id']) {
                DB::table('products')->insert($product);
            }
        }
    }
}
