<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('orders')->delete();
        
        \DB::table('orders')->insert(array (
            0 => 
            array (
                'id' => 8,
                'user_id' => 1,
                'grand_total' => '9898999.00',
                'payment_method' => 'stripe',
                'payment_status' => 'pending',
                'status' => 'new',
                'currency' => 'idr',
                'shipping_amount' => NULL,
                'shipping_method' => 'sicepat',
                'notes' => 'Test TEST ORDER 123 TEST TEST',
                'created_at' => '2024-11-23 17:26:04',
                'updated_at' => '2024-11-25 06:42:28',
            ),
            1 => 
            array (
                'id' => 9,
                'user_id' => 1,
                'grand_total' => '121292985.00',
                'payment_method' => 'COD',
                'payment_status' => 'pending',
                'status' => 'new',
                'currency' => 'idr',
                'shipping_amount' => NULL,
                'shipping_method' => 'jne',
                'notes' => NULL,
                'created_at' => '2024-11-25 06:01:21',
                'updated_at' => '2024-11-25 06:42:33',
            ),
        ));
        
        
    }
}