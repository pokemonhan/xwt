<?php

use Illuminate\Database\Seeder;

class FrontendUsersAccountsTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_accounts_types')->delete();
        
        \DB::table('frontend_users_accounts_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '充值',
                'sign' => 'recharge',
                'in_out' => 1,
                'param' => '1,2',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 3,
                'name' => '下级返点',
                'sign' => 'point_from_child',
                'in_out' => 1,
                'param' => '1,2,3,4,5,6,7,8',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 4,
                'name' => '游戏奖金',
                'sign' => 'game_bonus',
                'in_out' => 1,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 6,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 5,
                'name' => '撤单返款',
                'sign' => 'cancel_order',
                'in_out' => 1,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 2,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 6,
                'name' => '活动礼金',
                'sign' => 'gift',
                'in_out' => 1,
                'param' => '1,2',
                'frozen_type' => 0,
                'activity_sign' => 1,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 7,
                'name' => '上级充值',
                'sign' => 'recharge_from_parent',
                'in_out' => 1,
                'param' => '1,2,7',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 8,
                'name' => '系统活动转账',
                'sign' => 'system_claim',
                'in_out' => 1,
                'param' => '1,2,8',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 9,
                'name' => '日工资',
                'sign' => 'day_salary',
                'in_out' => 1,
                'param' => '1,2',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 10,
                'name' => '上级分红',
                'sign' => 'dividend_from_parent',
                'in_out' => 1,
                'param' => '1,2,7',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 11,
                'name' => '提现解冻',
                'sign' => 'withdraw_un_frozen',
                'in_out' => 1,
                'param' => '1,2',
                'frozen_type' => 2,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 12,
                'name' => '提现冻结',
                'sign' => 'withdraw_frozen',
                'in_out' => 2,
                'param' => '1,2',
                'frozen_type' => 1,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 13,
                'name' => '提现成功',
                'sign' => 'withdraw_finish',
                'in_out' => 2,
                'param' => '1,2',
                'frozen_type' => 4,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 14,
                'name' => '投注扣款',
                'sign' => 'bet_cost',
                'in_out' => 2,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 1,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 15,
                'name' => '追号冻结',
                'sign' => 'trace_cost',
                'in_out' => 2,
                'param' => '1,2,4,5,6',
                'frozen_type' => 1,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 16,
                'name' => '真实扣款',
                'sign' => 'real_cost',
                'in_out' => 2,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 4,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 17,
                'name' => '撤销返点',
                'sign' => 'cancel_point',
                'in_out' => 2,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 18,
                'name' => '撤销派奖',
                'sign' => 'cancel_bonus',
                'in_out' => 2,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 19,
                'name' => '撤单手续费',
                'sign' => 'cancel_fee',
                'in_out' => 2,
                'param' => '1,2,3,4,5,6',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 20,
                'name' => '为下级充值',
                'sign' => 'recharge_to_child',
                'in_out' => 2,
                'param' => '1,2,9',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 21,
                'name' => '系统扣减',
                'sign' => 'system_reduce',
                'in_out' => 2,
                'param' => '1,2,8',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 22,
                'name' => '分红给下级',
                'sign' => 'dividend_to_child',
                'in_out' => 2,
                'param' => '1,2,9',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 23,
                'name' => '奖金限额扣除',
                'sign' => 'bonus_limit_reduce',
                'in_out' => 2,
                'param' => '1,2',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 24,
                'name' => '人工充值',
                'sign' => 'artificial_recharge',
                'in_out' => 1,
                'param' => '1,2',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 25,
                'name' => '人工扣款',
                'sign' => 'artificial_deduction',
                'in_out' => 2,
                'param' => '1,2',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => '2019-06-29 14:37:28',
            ),
            24 => 
            array (
                'id' => 26,
                'name' => '投注返点',
                'sign' => 'bet_commission',
                'in_out' => 1,
                'param' => '1,2,3,4,6',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 27,
                'name' => '下级投注反佣',
                'sign' => 'commission',
                'in_out' => 1,
                'param' => '1,2,3,4,6',
                'frozen_type' => 0,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 28,
                'name' => '追号解冻',
                'sign' => 'trace_un_frozen',
                'in_out' => 1,
                'param' => '1,2,4,5,6',
                'frozen_type' => 2,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 29,
                'name' => '追号返款',
                'sign' => 'trace_refund',
                'in_out' => 1,
                'param' => '1,2,4,5',
                'frozen_type' => 2,
                'activity_sign' => 0,
                'admin_id' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}