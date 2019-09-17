<?php

use Illuminate\Database\Seeder;

class BackendAdminRechargePocessAmountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_recharge_pocess_amounts')->delete();
        
        \DB::table('backend_admin_recharge_pocess_amounts')->insert(array (
            0 => 
            array (
                'id' => 8,
                'admin_id' => 27,
                'fund' => '30000.00',
                'created_at' => '2019-05-10 16:16:06',
                'updated_at' => '2019-07-15 12:33:25',
            ),
            1 => 
            array (
                'id' => 9,
                'admin_id' => 28,
                'fund' => '0.00',
                'created_at' => '2019-05-10 16:18:54',
                'updated_at' => '2019-05-10 16:18:54',
            ),
            2 => 
            array (
                'id' => 10,
                'admin_id' => 13,
                'fund' => '0.00',
                'created_at' => '2019-05-11 16:08:56',
                'updated_at' => '2019-05-11 16:08:56',
            ),
            3 => 
            array (
                'id' => 11,
                'admin_id' => 20,
                'fund' => '10000.00',
                'created_at' => '2019-05-11 16:08:56',
                'updated_at' => '2019-05-23 17:45:10',
            ),
            4 => 
            array (
                'id' => 15,
                'admin_id' => 31,
                'fund' => '0.00',
                'created_at' => '2019-07-11 17:27:55',
                'updated_at' => '2019-07-11 17:27:55',
            ),
            5 => 
            array (
                'id' => 16,
                'admin_id' => 32,
                'fund' => '0.00',
                'created_at' => '2019-07-11 17:28:33',
                'updated_at' => '2019-07-11 17:28:33',
            ),
            6 => 
            array (
                'id' => 18,
                'admin_id' => 25,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 15:24:38',
                'updated_at' => '2019-07-16 00:00:01',
            ),
            7 => 
            array (
                'id' => 19,
                'admin_id' => 26,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 15:24:38',
                'updated_at' => '2019-07-16 00:00:01',
            ),
            8 => 
            array (
                'id' => 20,
                'admin_id' => 34,
                'fund' => '95000.00',
                'created_at' => '2019-07-15 15:24:38',
                'updated_at' => '2019-07-19 15:23:16',
            ),
            9 => 
            array (
                'id' => 21,
                'admin_id' => 36,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 15:24:38',
                'updated_at' => '2019-07-16 00:00:01',
            ),
            10 => 
            array (
                'id' => 22,
                'admin_id' => 38,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 15:24:38',
                'updated_at' => '2019-07-16 00:00:01',
            ),
            11 => 
            array (
                'id' => 23,
                'admin_id' => 39,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 16:45:18',
                'updated_at' => '2019-07-16 00:00:02',
            ),
            12 => 
            array (
                'id' => 24,
                'admin_id' => 40,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 16:53:03',
                'updated_at' => '2019-07-16 00:00:02',
            ),
            13 => 
            array (
                'id' => 25,
                'admin_id' => 41,
                'fund' => '90000.00',
                'created_at' => '2019-07-15 16:54:34',
                'updated_at' => '2019-07-16 00:00:02',
            ),
        ));
        
        
    }
}