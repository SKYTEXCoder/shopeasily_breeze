<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 6,
                'name' => 'Laptops',
                'slug' => 'laptops',
                'image' => 'categories/01JDC0ZHNJ1F6PKP3SCPKJAA22.png',
                'is_active' => 1,
                'created_at' => '2024-11-23 08:07:29',
                'updated_at' => '2024-11-23 08:07:29',
                'description' => NULL,
                'parent_category_id' => NULL,
            ),
        ));
        
        
    }
}