<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('services')->truncate();

        $services = [
            [
                'name' => 'Freight Forwarding',
                'description' => 'Air, sea, and road freight solutions with global coverage and competitive pricing.',
                'icon' => 'fas fa-plane',
                'category' => 'Shipping', // Changed from category_id
                'features' => 'Global Coverage, Competitive Pricing, Multiple Transport Options',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Warehousing & Storage',
                'description' => 'Secure storage solutions with inventory management and distribution services.',
                'icon' => 'fas fa-warehouse',
                'category' => 'Storage',
                'features' => 'Secure Storage, Inventory Management, Distribution Services',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Last-Mile Delivery',
                'description' => 'Efficient final delivery to customers with real-time tracking and notifications.',
                'icon' => 'fas fa-truck',
                'category' => 'Delivery',
                'features' => 'Real-time Tracking, Customer Notifications, Efficient Routing',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supply Chain Management',
                'description' => 'End-to-end supply chain optimization and visibility solutions.',
                'icon' => 'fas fa-link',
                'category' => 'Management',
                'features' => 'End-to-End Visibility, Optimization, Analytics',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Customs Clearance',
                'description' => 'Expert handling of customs documentation and clearance processes.',
                'icon' => 'fas fa-passport',
                'category' => 'International',
                'features' => 'Documentation, Expert Handling, Fast Processing',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Real-Time Tracking',
                'description' => 'Advanced tracking systems providing real-time visibility of your shipments.',
                'icon' => 'fas fa-satellite',
                'category' => 'Technology',
                'features' => 'Real-time Updates, GPS Tracking, Notifications',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert services
        Service::insert($services);

        $this->command->info('Services seeded successfully!');
    }
}
