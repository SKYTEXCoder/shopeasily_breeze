<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all table seeders
        $this->call([
            UsersTableSeeder::class,
            BrandsTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            WishlistsTableSeeder::class,
            WishlistProductsTableSeeder::class,
            ShippingInformationTableSeeder::class,
            OrdersTableSeeder::class,
            OrderProductsTableSeeder::class,
            AddressesTableSeeder::class,
            PaymentsTableSeeder::class,
        ]);
    }
}
