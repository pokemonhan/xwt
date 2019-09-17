<?php

use Illuminate\Database\Seeder;

class BackendAdminAccessGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_access_groups')->delete();
        
        \DB::table('backend_admin_access_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'group_name' => '超级管理组',
                'role' => '*',
                'status' => 1,
                'created_at' => '2019-04-10 10:10:56',
                'updated_at' => '2019-04-10 10:10:51',
                'platform_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'group_name' => '彩票组',
                'role' => '[2,1,3,11,59,5,64,9,65,66,69,73,70,104,103,105,106,107,108,109,110,112,13,12,14,15,16,17,18,19,20,21,22,23,24,26,25,44,43,60,27]',
                'status' => 1,
                'created_at' => '2019-07-24 11:36:21',
                'updated_at' => '2019-07-24 11:36:21',
                'platform_id' => 1,
            ),
            2 => 
            array (
                'id' => 13,
                'group_name' => '客服组',
                'role' => '[1,2,3,11,59,64,5,9,65,66,73,69,70,103,104,105,106,107,108,109,110,117,112,114,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,60,28,29,30,32,33,34,35,36,38,40,41,42,61,79,80,97,99,43,44,45,47,48,49,115,81,82,98,100,111,113,118,101,102]',
                'status' => 1,
                'created_at' => '2019-08-15 14:07:01',
                'updated_at' => '2019-08-15 14:07:01',
                'platform_id' => 1,
            ),
        ));
        
        
    }
}