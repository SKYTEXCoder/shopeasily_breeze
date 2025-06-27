<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // Only seed if users exist
        if (DB::table('users')->count() > 0) {
            $wishlists = [
                [
                    'user_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('wishlists')->insert($wishlists);
        }
    }
}
