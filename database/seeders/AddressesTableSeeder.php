<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('addresses')->delete();
        
        \DB::table('addresses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 8,
                'first_name' => 'Dandy Arya',
                'last_name' => 'Akbar',
                'phone' => '6285776698343',
                'street_address' => 'amogus',
                'city' => 'Bekasi',
                'state' => 'Grand Wisata',
                'zip_code' => '17510',
                'created_at' => '2024-11-25 05:50:06',
                'updated_at' => '2024-11-25 05:50:06',
            ),
        ));
        
        
    }
}