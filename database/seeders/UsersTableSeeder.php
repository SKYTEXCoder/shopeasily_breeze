<?php

namespace Database\Seeders;

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
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Dandy Arya Akbar',
                'email' => 'dandyarya003@gmail.com',
                'email_verified_at' => '2024-11-20 12:03:08',
                'password' => '$2y$12$JGMN566fBQ1jvcdIPsGitemyFjDZUl17JzgulCRkDv2H3SmXTcUru',
                'remember_token' => 'zNKaiKX109HlUmfBm9lfnn1AeHVluYGgnd0XWdXEpqcPJ3gRFYDwFWDltG25',
                'created_at' => '2024-11-20 11:43:26',
                'updated_at' => '2024-11-20 12:13:54',
            ),
            1 => 
            array (
                'id' => 8,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => '2024-11-23 05:19:19',
                'password' => '$2y$12$S8axFcMr9zzR73hrpEhX4ODf7E9/QvJKc7I.oQ.MziB0upUYodPse',
                'remember_token' => 'nXVpKAHdZ0W7RlJYVfdW0l6E2DtuIRivJbONqcl1e8jQvMhVaCTuky0EMo1w',
                'created_at' => '2024-11-23 05:19:41',
                'updated_at' => '2024-11-23 05:19:41',
            ),
        ));
        
        
    }
}