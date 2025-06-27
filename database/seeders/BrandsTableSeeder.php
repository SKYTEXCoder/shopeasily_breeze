<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name' => 'Intel',
                'slug' => 'intel',
                'image' => 'brands/intel.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMD',
                'slug' => 'amd',
                'image' => 'brands/amd.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NVIDIA',
                'slug' => 'nvidia',
                'image' => 'brands/nvidia.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS',
                'slug' => 'asus',
                'image' => 'brands/asus.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI',
                'slug' => 'msi',
                'image' => 'brands/msi.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Corsair',
                'slug' => 'corsair',
                'image' => 'brands/corsair.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gigabyte',
                'slug' => 'gigabyte',
                'image' => 'brands/gigabyte.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'image' => 'brands/samsung.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Western Digital',
                'slug' => 'western-digital',
                'image' => 'brands/western-digital.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seagate',
                'slug' => 'seagate',
                'image' => 'brands/seagate.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}
