<?php

use Illuminate\Database\Seeder;

class BackendAdminRechargePermitGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_admin_recharge_permit_groups')->delete();
        
        \DB::table('backend_admin_recharge_permit_groups')->insert(array (
            0 => 
            array (
                'id' => 12,
                'group_id' => 13,
                'group_name' => '客服组',
                'created_at' => '2019-08-15 14:07:01',
                'updated_at' => '2019-08-15 14:07:01',
            ),
        ));
        
        
    }
}