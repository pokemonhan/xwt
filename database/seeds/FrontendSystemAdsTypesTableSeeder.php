<?php

use Illuminate\Database\Seeder;

class FrontendSystemAdsTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_system_ads_types')->delete();
        
        \DB::table('frontend_system_ads_types')->insert(array (
            0 => 
            array (
                'id' => 14,
                'name' => '顶部导航',
                'type' => 1,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => '2019-05-17 17:49:12',
                'ext_type' => 1,
                'l_size' => 100,
                'w_size' => 1080,
                'size' => 2000,
            ),
            1 => 
            array (
                'id' => 15,
                'name' => '注册页右侧-图片广告',
                'type' => 2,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => '2019-05-17 17:49:53',
                'ext_type' => 1,
                'l_size' => 100,
                'w_size' => 1080,
                'size' => 2000,
            ),
            2 => 
            array (
                'id' => 16,
                'name' => '用户首页轮播图',
                'type' => 3,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => '2019-08-30 15:20:00',
                'ext_type' => 1,
                'l_size' => 100,
                'w_size' => 1080,
                'size' => 2000,
            ),
            3 => 
            array (
                'id' => 17,
                'name' => '登录页背景',
                'type' => 4,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => '2019-08-30 15:19:44',
                'ext_type' => 1,
                'l_size' => 102,
                'w_size' => 1080,
                'size' => 2000,
            ),
            4 => 
            array (
                'id' => 18,
                'name' => '品牌首页视频',
                'type' => 5,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => '2019-08-30 15:18:20',
                'ext_type' => 2,
                'l_size' => 123,
                'w_size' => 806,
                'size' => NULL,
            ),
            5 => 
            array (
                'id' => 19,
                'name' => '总代首页-右中',
                'type' => 6,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 1,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            6 => 
            array (
                'id' => 20,
                'name' => '普代首页-右中',
                'type' => 7,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 1,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            7 => 
            array (
                'id' => 21,
                'name' => '代理中心-右下',
                'type' => 8,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 1,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            8 => 
            array (
                'id' => 22,
                'name' => '新版首页大图轮播',
                'type' => 9,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 1,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            9 => 
            array (
                'id' => 23,
                'name' => '登录-广告位',
                'type' => 10,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 3,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            10 => 
            array (
                'id' => 24,
                'name' => '首页-广告位',
                'type' => 11,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 3,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            11 => 
            array (
                'id' => 25,
                'name' => '注册-广告位',
                'type' => 12,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'ext_type' => 3,
                'l_size' => NULL,
                'w_size' => NULL,
                'size' => NULL,
            ),
            12 => 
            array (
                'id' => 26,
                'name' => 'APP活动列表',
                'type' => 13,
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => '2019-05-11 14:00:41',
                'ext_type' => 1,
                'l_size' => 222,
                'w_size' => 1080,
                'size' => 100,
            ),
        ));
        
        
    }
}