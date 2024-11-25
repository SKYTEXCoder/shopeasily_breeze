<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category_id' => 6,
                'brand_id' => 2,
                'name' => 'ASUS VivoBook Max X541',
                'slug' => 'asus-vivobook-max-x541',
                'images' => '["products\\/01JDC18RGCBVWV4Z3DH1SW0TP9.png"]',
                'description' => 'ASUS VIVOBOOK MAX X541 LAPTOP',
                'final_price' => '6499999.00',
                'original_price' => NULL,
                'discount_percentage' => 0,
                'stock_amount' => 1,
                'sold_amount' => 0,
                'is_active' => 1,
                'is_featured' => 1,
                'in_stock' => 1,
                'on_sale' => 1,
                'rating' => '0.0',
                'created_at' => '2024-11-23 08:12:31',
                'updated_at' => '2024-11-23 14:09:59',
            ),
            1 => 
            array (
                'id' => 2,
                'category_id' => 6,
                'brand_id' => 2,
                'name' => 'SONY WH-1000XM4 Midnight Blue Wireless NC Headphone / 1000XM4 / 1000X',
                'slug' => 'sony-wh-1000xm4-midnight-blue-wireless-nc-headphone-1000xm4-1000x',
                'images' => '["products\\/01JDCTN7P6YAKZPX5YK9QWAV8H.jpg","products\\/01JDCTN7PACWCRFVPCDA0Y5M28.jpg","products\\/01JDCTN7PCM9PZGF1PWV5KY76X.jpg","products\\/01JDCTN7PF6XJ6X53Q5648S5GX.jpg","products\\/01JDCTN7PK9QM0R52062H0G57P.jpg","products\\/01JDCTN7PQAFPR2HW0R110SMDB.jpg"]',
                'description' => 'ONLY MUSIC.
NOTHING ELSE.
Sony’s intelligent industry-leading noise canceling1 headphones with premium sound elevate your listening experience with the ability to personalize and control everything you hear.

Industry-leading noise cancelation4
Industry-leading noise cancellation technology4 means you hear every word, note, and tune with incredible clarity, no matter your environment.

The technology that makes it happen
Dual noise sensor technology, featuring two microphones on each earcup, captures ambient noise and passes the data to the HD Noise-Canceling Processor QN1. Using a new algorithm, the HD Noise-Canceling Processor QN1 then applies noise-canceling processing in real time to a range of acoustic environments.

Optimizing noise canceling
To maximize noise-canceling performance, the WH-1000XM4 headphones incorporate two technologies: Personal Noise-Canceling Optimizer, designed specifically for you, and Atmospheric Pressure Optimizing, designed specifically for air travel.

Wireless freedom, premium sound
LDAC transmits approximately three times more data (at the maximum transfer rate of 990 kbps) than conventional BLUETOOTH® audio, allowing you to enjoy High-Resolution Audio content in exceptional quality, as close as possible to that of a dedicated wired connection.

Sound you can believe in
A built-in analog amplifier integrated in the HD Noise-Canceling Processor QN1 realizes an unmatched signal-to-noise ratio for low distortion and exceptional sound quality for portable devices.

Sound you can believe in
A built-in analog amplifier integrated in the HD Noise-Canceling Processor QN1 realizes an unmatched signal-to-noise ratio for low distortion and exceptional sound quality for portable devices.',
                'final_price' => '3399000.00',
                'original_price' => NULL,
                'discount_percentage' => 0,
                'stock_amount' => 1,
                'sold_amount' => 0,
                'is_active' => 1,
                'is_featured' => 1,
                'in_stock' => 1,
                'on_sale' => 1,
                'rating' => '0.0',
                'created_at' => '2024-11-23 15:36:14',
                'updated_at' => '2024-11-23 15:36:14',
            ),
        ));
        
        
    }
}