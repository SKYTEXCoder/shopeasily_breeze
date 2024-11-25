<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_products')->delete();
        
        \DB::table('order_products')->insert(array (
            0 => 
            array (
                'id' => 13,
                'order_id' => 8,
                'product_id' => 1,
                'quantity' => 1,
                'unit_amount' => '6499999.00',
                'total_amount' => '6499999.00',
                'created_at' => '2024-11-23 17:26:04',
                'updated_at' => '2024-11-25 05:48:44',
            ),
            1 => 
            array (
                'id' => 14,
                'order_id' => 8,
                'product_id' => 2,
                'quantity' => 1,
                'unit_amount' => '3399000.00',
                'total_amount' => '3399000.00',
                'created_at' => '2024-11-23 17:26:04',
                'updated_at' => '2024-11-25 05:48:44',
            ),
            2 => 
            array (
                'id' => 15,
                'order_id' => 9,
                'product_id' => 1,
                'quantity' => 15,
                'unit_amount' => '6499999.00',
                'total_amount' => '97499985.00',
                'created_at' => '2024-11-25 06:01:21',
                'updated_at' => '2024-11-25 06:01:21',
            ),
            3 => 
            array (
                'id' => 16,
                'order_id' => 9,
                'product_id' => 2,
                'quantity' => 7,
                'unit_amount' => '3399000.00',
                'total_amount' => '23793000.00',
                'created_at' => '2024-11-25 06:01:21',
                'updated_at' => '2024-11-25 06:01:21',
            ),
        ));
        
        
    }
}