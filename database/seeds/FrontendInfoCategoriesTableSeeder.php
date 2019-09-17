<?php

use Illuminate\Database\Seeder;

class FrontendInfoCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_info_categories')->delete();
        
        \DB::table('frontend_info_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '系统帮助',
                'parent' => 0,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '平台公告',
                'parent' => 0,
                'template' => 'Default',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => '帮助中心',
                'parent' => 0,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'title' => '如何注册',
                'parent' => 3,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'title' => '如何充值',
                'parent' => 3,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'title' => '玩法介绍',
                'parent' => 3,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'title' => '如何兑奖',
                'parent' => 3,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'title' => '如何提款',
                'parent' => 3,
                'template' => 'Help',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'title' => '游戏撤单',
                'parent' => 0,
                'template' => 'Default',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'title' => '银行维护',
                'parent' => 0,
                'template' => 'Default',
                'platform_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}