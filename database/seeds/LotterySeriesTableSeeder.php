<?php

use Illuminate\Database\Seeder;

class LotterySeriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lottery_series')->delete();
        
        \DB::table('lottery_series')->insert(array (
            0 => 
            array (
                'id' => 1,
                'series_name' => 'ssc',
                'title' => '时时彩',
                'status' => 1,
                'encode_splitter' => NULL,
                'price_difference' => 0,
                'updated_at' => '2019-08-23 14:51:55',
                'created_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'series_name' => 'lotto',
                'title' => '乐透',
                'status' => 1,
                'encode_splitter' => ' ',
                'price_difference' => 20,
                'updated_at' => '2019-08-23 19:52:57',
                'created_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'series_name' => 'k3',
                'title' => '快三',
                'status' => 1,
                'encode_splitter' => NULL,
                'price_difference' => 0,
                'updated_at' => NULL,
                'created_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'series_name' => 'sd',
                'title' => '3D',
                'status' => 1,
                'encode_splitter' => NULL,
                'price_difference' => 30,
                'updated_at' => NULL,
                'created_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'series_name' => 'ssl',
                'title' => '时时乐',
                'status' => 1,
                'encode_splitter' => NULL,
                'price_difference' => 30,
                'updated_at' => '2019-08-26 14:35:42',
                'created_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'series_name' => 'p3p5',
                'title' => '排列35',
                'status' => 1,
                'encode_splitter' => NULL,
                'price_difference' => 30,
                'updated_at' => NULL,
                'created_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'series_name' => 'lhc',
                'title' => '六合彩',
                'status' => 1,
                'encode_splitter' => ' ',
                'price_difference' => 0,
                'updated_at' => NULL,
                'created_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'series_name' => 'pk10',
                'title' => 'pk10',
                'status' => 1,
                'encode_splitter' => ',',
                'price_difference' => 0,
                'updated_at' => NULL,
                'created_at' => NULL,
            ),
        ));
        
        
    }
}