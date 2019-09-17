<?php

use Illuminate\Database\Seeder;

class FrontendUsersPrivacyFlowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_privacy_flows')->delete();
        
        \DB::table('frontend_users_privacy_flows')->insert(array (
            0 => 
            array (
                'id' => 1,
                'admin_id' => 23,
                'admin_name' => 'Diana',
                'user_id' => 502,
                'username' => '啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-05-11 18:53:54',
                'created_at' => '2019-05-11 18:53:54',
            ),
            1 => 
            array (
                'id' => 2,
                'admin_id' => 23,
                'admin_name' => 'Diana',
                'user_id' => 14,
                'username' => 'diana111',
                'comment' => '[禁止提现] ==>此用户异常',
                'updated_at' => '2019-07-16 18:13:10',
                'created_at' => '2019-07-16 18:13:10',
            ),
            2 => 
            array (
                'id' => 3,
                'admin_id' => 23,
                'admin_name' => 'Diana',
                'user_id' => 14,
                'username' => 'diana111',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-07-16 18:13:14',
                'created_at' => '2019-07-16 18:13:14',
            ),
            3 => 
            array (
                'id' => 4,
                'admin_id' => 23,
                'admin_name' => 'Diana',
                'user_id' => 14,
                'username' => 'diana111',
                'comment' => '[禁止登录] ==>123',
                'updated_at' => '2019-07-16 18:13:45',
                'created_at' => '2019-07-16 18:13:45',
            ),
            4 => 
            array (
                'id' => 5,
                'admin_id' => 4,
                'admin_name' => 'york',
                'user_id' => 18,
                'username' => 'mike111',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-07-24 12:10:41',
                'created_at' => '2019-07-24 12:10:41',
            ),
            5 => 
            array (
                'id' => 6,
                'admin_id' => 4,
                'admin_name' => 'york',
                'user_id' => 18,
                'username' => 'mike111',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-07-24 12:11:22',
                'created_at' => '2019-07-24 12:11:22',
            ),
            6 => 
            array (
                'id' => 7,
                'admin_id' => 4,
                'admin_name' => 'york',
                'user_id' => 18,
                'username' => 'mike111',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-07-24 12:14:19',
                'created_at' => '2019-07-24 12:14:19',
            ),
            7 => 
            array (
                'id' => 8,
                'admin_id' => 4,
                'admin_name' => 'york',
                'user_id' => 18,
                'username' => 'mike111',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-07-24 12:16:42',
                'created_at' => '2019-07-24 12:16:42',
            ),
            8 => 
            array (
                'id' => 9,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 16,
                'username' => 'ling1',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-06 17:06:23',
                'created_at' => '2019-08-06 17:06:23',
            ),
            9 => 
            array (
                'id' => 10,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-08-07 11:12:26',
                'created_at' => '2019-08-07 11:12:26',
            ),
            10 => 
            array (
                'id' => 11,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 16,
                'username' => 'ling1',
                'comment' => '[禁止提现] ==>此用户异常',
                'updated_at' => '2019-08-07 14:41:02',
                'created_at' => '2019-08-07 14:41:02',
            ),
            11 => 
            array (
                'id' => 12,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止投注] ==>c测试',
                'updated_at' => '2019-08-07 15:25:52',
                'created_at' => '2019-08-07 15:25:52',
            ),
            12 => 
            array (
                'id' => 13,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-08-07 15:26:35',
                'created_at' => '2019-08-07 15:26:35',
            ),
            13 => 
            array (
                'id' => 14,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-08-07 15:43:51',
                'created_at' => '2019-08-07 15:43:51',
            ),
            14 => 
            array (
                'id' => 15,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-08-07 15:47:02',
                'created_at' => '2019-08-07 15:47:02',
            ),
            15 => 
            array (
                'id' => 16,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止提现] ==>此用户异常',
                'updated_at' => '2019-08-07 15:48:40',
                'created_at' => '2019-08-07 15:48:40',
            ),
            16 => 
            array (
                'id' => 17,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止资金操作] ==>此用户异常',
                'updated_at' => '2019-08-07 15:49:21',
                'created_at' => '2019-08-07 15:49:21',
            ),
            17 => 
            array (
                'id' => 18,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-08-07 15:50:46',
                'created_at' => '2019-08-07 15:50:46',
            ),
            18 => 
            array (
                'id' => 19,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-07 15:52:37',
                'created_at' => '2019-08-07 15:52:37',
            ),
            19 => 
            array (
                'id' => 20,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-07 16:21:19',
                'created_at' => '2019-08-07 16:21:19',
            ),
            20 => 
            array (
                'id' => 21,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止提现] ==>此用户异常',
                'updated_at' => '2019-08-07 16:25:29',
                'created_at' => '2019-08-07 16:25:29',
            ),
            21 => 
            array (
                'id' => 22,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-08-07 16:25:34',
                'created_at' => '2019-08-07 16:25:34',
            ),
            22 => 
            array (
                'id' => 23,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止资金操作] ==>此用户异常',
                'updated_at' => '2019-08-07 16:27:44',
                'created_at' => '2019-08-07 16:27:44',
            ),
            23 => 
            array (
                'id' => 24,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止提现] ==>此用户异常',
                'updated_at' => '2019-08-07 16:28:12',
                'created_at' => '2019-08-07 16:28:12',
            ),
            24 => 
            array (
                'id' => 25,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-07 16:47:01',
                'created_at' => '2019-08-07 16:47:01',
            ),
            25 => 
            array (
                'id' => 26,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止资金操作] ==>此用户异常',
                'updated_at' => '2019-08-07 16:55:40',
                'created_at' => '2019-08-07 16:55:40',
            ),
            26 => 
            array (
                'id' => 27,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-08-07 16:57:36',
                'created_at' => '2019-08-07 16:57:36',
            ),
            27 => 
            array (
                'id' => 28,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-07 17:02:25',
                'created_at' => '2019-08-07 17:02:25',
            ),
            28 => 
            array (
                'id' => 29,
                'admin_id' => 24,
                'admin_name' => 'Ling',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[禁止资金操作] ==>此用户异常',
                'updated_at' => '2019-08-07 17:03:51',
                'created_at' => '2019-08-07 17:03:51',
            ),
            29 => 
            array (
                'id' => 30,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 21,
                'username' => 'jeff',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-07 17:24:06',
                'created_at' => '2019-08-07 17:24:06',
            ),
            30 => 
            array (
                'id' => 31,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止登录] ==>此用户异常',
                'updated_at' => '2019-08-07 18:04:12',
                'created_at' => '2019-08-07 18:04:12',
            ),
            31 => 
            array (
                'id' => 32,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-07 18:04:20',
                'created_at' => '2019-08-07 18:04:20',
            ),
            32 => 
            array (
                'id' => 33,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[禁止投注] ==>此用户异常',
                'updated_at' => '2019-08-08 15:59:35',
                'created_at' => '2019-08-08 15:59:35',
            ),
            33 => 
            array (
                'id' => 34,
                'admin_id' => 43,
                'admin_name' => 'max1111',
                'user_id' => 3,
                'username' => 'kiki',
                'comment' => '[开放用户] ==>此用户异常',
                'updated_at' => '2019-08-08 16:07:57',
                'created_at' => '2019-08-08 16:07:57',
            ),
        ));
        
        
    }
}