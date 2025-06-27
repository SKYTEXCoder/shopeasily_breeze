<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Processors (CPU)',
                'slug' => 'processors-cpu',
                'image' => 'categories/processors.jpg',
                'description' => 'High-performance processors for gaming and productivity.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Graphics Cards (GPU)',
                'slug' => 'graphics-cards-gpu',
                'image' => 'categories/graphics-cards.jpg',
                'description' => 'High-performance graphics cards for gaming and content creation.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Motherboards',
                'slug' => 'motherboards',
                'image' => 'categories/motherboards.jpg',
                'description' => 'Motherboards for various CPU sockets and form factors.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Memory (RAM)',
                'slug' => 'memory-ram',
                'image' => 'categories/memory.jpg',
                'description' => 'High-performance RAM for gaming and multitasking.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Storage (SSD/HDD)',
                'slug' => 'storage-ssd-hdd',
                'image' => 'categories/storage.jpg',
                'description' => 'High-speed SSDs and reliable HDDs for all your storage needs.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Power Supplies (PSU)',
                'slug' => 'power-supplies-psu',
                'image' => 'categories/power-supplies.jpg',
                'description' => 'Reliable power supplies for stable system performance.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PC Cases',
                'slug' => 'pc-cases',
                'image' => 'categories/pc-cases.jpg',
                'description' => 'Stylish and functional PC cases for all builds.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cooling & Fans',
                'slug' => 'cooling-fans',
                'image' => 'categories/cooling.jpg',
                'description' => 'Cooling solutions and fans for optimal airflow.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
