<?php

namespace Database\Seeders;
use App\Models\Service;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ServicesTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ProductsTableSeeder;   
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            ServicesTableSeeder::class,
        ]);
    }
}
