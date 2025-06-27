<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Dandy Arya Akbar',
            'first_name' => 'Dandy',
            'last_name' => 'Arya Akbar',
            'email' => 'dandyarya003@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('niggerupo0O1'),
            'phone_number' => '085776698343',
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Muhammad Hafizh Rifan Rabtsani',
            'first_name' => 'Muhammad Hafizh Rifan',
            'last_name' => 'Rabtsani',
            'email' => 'hafzrfn@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('hafzrfn'),
            'phone_number' => '08313213124',
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Adhitya Wardhana',
            'first_name' => 'Adhitya',
            'last_name' => 'Wardhana',
            'email' => 'adhityawardhana@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('adhityawardhana'),
            'phone_number' => '0851231235123',
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Danar Priyo Utomo',
            'first_name' => 'Danar',
            'last_name' => 'Priyo Utomo',
            'email' => 'danarpriyoutomo@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('danarpriyoutomo'),
            'phone_number' => '0852139239321',
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Dexter Morgan',
            'first_name' => 'Dexter',
            'last_name' => 'Morgan',
            'email' => 'dextermorgan@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('dextermorgan'),
            'phone_number' => '521321312313',
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Joe Goldberg',
            'first_name' => 'Joe',
            'last_name' => 'Goldberg',
            'email' => 'joegoldberg@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('joegoldberg'),
            'phone_number' => '689218391832',
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Guinevere Beck',
            'first_name' => 'Guinevere',
            'last_name' => 'Beck',
            'email' => 'beckdelltest@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('beckdelltest'),
            'phone_number' => '6982192839813',
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Love Quinn',
            'first_name' => 'Love',
            'last_name' => 'Quinn',
            'email' => 'lovequinn@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('lovequinn'),
            'phone_number' => '68291839183',
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Kate Lockwood',
            'first_name' => 'Kate',
            'last_name' => 'Lockwood',
            'email' => 'katelockwood@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('katelockwood'),
            'phone_number' => '589218391832',
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'Delilah Alves',
            'first_name' => 'Delilah',
            'last_name' => 'Alves',
            'email' => 'delilahalves@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('delilahalves'),
            'phone_number' => '59239128398',
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
