<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('brands')->delete();
        
        \DB::table('brands')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'ASUS',
                'slug' => 'asus',
                'image' => 'brands/01JDC11TJW6BBDSAX6CEZDVSBA.png',
                'is_active' => 1,
                'created_at' => '2024-11-23 08:08:43',
                'updated_at' => '2024-11-23 08:08:43',
            ),
        ));
        
        
    }
}