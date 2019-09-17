<?php

use Illuminate\Database\Seeder;

class SystemPlatformsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_platforms')->delete();
        
        \DB::table('system_platforms')->insert(array (
            0 => 
            array (
                'platform_id' => 1,
                'platform_name' => 'aa',
                'platform_sign' => 'a',
                'status' => 1,
                'comments' => 'aa',
                'prize_group_min' => 1700,
                'prize_group_max' => 1960,
                'single_price' => 2,
                'open_mode' => '1|0.1|0.01',
                'admin_id' => NULL,
                'last_admin_id' => NULL,
                'created_at' => '2019-03-29 23:50:58',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'platform_id' => 2,
                'platform_name' => 'bb',
                'platform_sign' => 'b',
                'status' => 1,
                'comments' => 'bb',
                'prize_group_min' => 1700,
                'prize_group_max' => 1960,
                'single_price' => 2,
                'open_mode' => '1|0.1|0.01',
                'admin_id' => NULL,
                'last_admin_id' => NULL,
                'created_at' => '2019-03-29 23:50:58',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}