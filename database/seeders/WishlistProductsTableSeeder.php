<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WishlistProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wishlist_products')->delete();
        
        
        
    }
}