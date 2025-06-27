<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // Only seed if wishlists and products exist
        if (DB::table('wishlists')->count() > 0 && DB::table('products')->count() > 0) {
            $wishlistProducts = [
                [
                    'wishlist_id' => 1,
                    'product_id' => 1, // Intel Core i9-14900K
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'wishlist_id' => 1,
                    'product_id' => 3, // NVIDIA GeForce RTX 4080 SUPER
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'wishlist_id' => 1,
                    'product_id' => 9, // Samsung 980 PRO 2TB NVMe SSD
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('wishlist_products')->insert($wishlistProducts);
        }
    }
}
