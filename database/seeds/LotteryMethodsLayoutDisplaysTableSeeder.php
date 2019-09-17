<?php

use Illuminate\Database\Seeder;

class LotteryMethodsLayoutDisplaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lottery_methods_layout_displays')->delete();
        
        \DB::table('lottery_methods_layout_displays')->insert(array (
            0 => 
            array (
                'id' => 1,
                'display_code' => 'W',
                'display_name' => '万位',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'display_code' => 'Q',
                'display_name' => '千位',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'display_code' => 'B',
                'display_name' => '百位',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'display_code' => 'S',
                'display_name' => '十位',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'display_code' => 'G',
                'display_name' => '个位',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'display_code' => 'WXZU120',
                'display_name' => '组选120',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'display_code' => 'ECH',
                'display_name' => '二重号',
                'created_at' => '2019-07-08 15:28:32',
                'updated_at' => '2019-07-08 15:28:32',
            ),
            7 => 
            array (
                'id' => 8,
                'display_code' => 'DH',
                'display_name' => '单号',
                'created_at' => '2019-07-08 15:28:39',
                'updated_at' => '2019-07-08 15:28:39',
            ),
            8 => 
            array (
                'id' => 9,
                'display_code' => 'SCH',
                'display_name' => '三重号',
                'created_at' => '2019-07-08 15:28:44',
                'updated_at' => '2019-07-08 15:28:44',
            ),
            9 => 
            array (
                'id' => 10,
                'display_code' => 'SCH',
                'display_name' => '四重号',
                'created_at' => '2019-07-08 15:28:47',
                'updated_at' => '2019-07-08 15:28:47',
            ),
            10 => 
            array (
                'id' => 11,
                'display_code' => 'YFFS',
                'display_name' => '一帆风顺',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'display_code' => 'HSCS',
                'display_name' => '好事成双',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'display_code' => 'SXBX',
                'display_name' => '三星报喜',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'display_code' => 'SJFC',
                'display_name' => '四季发财',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'display_code' => 'SXZU24',
                'display_name' => '组选24',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'display_code' => 'QZXHZ',
                'display_name' => '和值',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'display_code' => 'QZXKD',
                'display_name' => '跨度',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'display_code' => 'QZU3',
                'display_name' => '组三',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'display_code' => 'QZU6',
                'display_name' => '组六',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'display_code' => 'QZU3BD',
                'display_name' => '包胆',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'display_code' => 'QHZWS',
                'display_name' => '和值尾数',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'display_code' => 'QTS3',
                'display_name' => '特殊号',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'display_code' => 'BDW',
                'display_name' => '不定位',
                'created_at' => '2019-07-08 15:43:32',
                'updated_at' => '2019-07-08 15:43:32',
            ),
            23 => 
            array (
                'id' => 24,
                'display_code' => 'DTYS',
                'display_name' => '号码',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'display_code' => 'EBTH',
                'display_name' => '号码',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}