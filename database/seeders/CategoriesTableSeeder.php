<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Shipping',
            'Storage',
            'Delivery',
            'Management',
            'International',
            'Technology',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate([
                'name' => $name,
                'slug' => strtolower(str_replace(' ', '-', $name)),
            ]);
        }
    }
}

