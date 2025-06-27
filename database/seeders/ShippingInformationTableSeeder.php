<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if users exist
        if (DB::table('users')->count() > 0) {
            $shippingInfo = [
                [
                    'user_id' => 1,
                    'is_primary' => true,
                    'label' => 'Home',
                    'street_address' => 'Jl. Sudirman No. 123',
                    'address_line_2' => 'RT 01 RW 02',
                    'city_or_regency' => 'Jakarta',
                    'state' => 'DKI Jakarta',
                    'province' => 'DKI Jakarta',
                    'zip_code' => '12345',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('shipping_information')->insert($shippingInfo);
        }
    }
}
