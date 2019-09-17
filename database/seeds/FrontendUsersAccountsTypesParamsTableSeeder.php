<?php

use Illuminate\Database\Seeder;

class FrontendUsersAccountsTypesParamsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_accounts_types_params')->delete();
        
        \DB::table('frontend_users_accounts_types_params')->insert(array (
            0 => 
            array (
                'id' => 1,
                'label' => '金额',
                'param' => 'amount',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'label' => '用户id',
                'param' => 'user_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'label' => '投注id',
                'param' => 'project_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'label' => '彩种id',
                'param' => 'lottery_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'label' => '彩种玩法id',
                'param' => 'method_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'label' => '彩种奖期',
                'param' => 'issue',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'label' => '转账的用户id',
                'param' => 'from_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'label' => '转账的总代id',
                'param' => 'from_admin_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'label' => '接受转账的用户id',
                'param' => 'to_id',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}