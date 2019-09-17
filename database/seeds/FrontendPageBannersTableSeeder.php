<?php

use Illuminate\Database\Seeder;

class FrontendPageBannersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_page_banners')->delete();
        
        \DB::table('frontend_page_banners')->insert(array (
            0 => 
            array (
                'id' => 9,
                'title' => '扑克联赛',
                'content' => '扑克联赛',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/f0f0d95e9bda2d1751dd3d9eb361fd4f.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_f0f0d95e9bda2d1751dd3d9eb361fd4f.jpg',
                'type' => 1,
                'redirect_url' => '/test/test',
                'activity_id' => NULL,
                'status' => 1,
                'start_time' => '2018-05-05 00:00:00',
                'end_time' => '2019-05-05 00:00:00',
                'sort' => 2,
                'created_at' => '2019-08-06 15:36:06',
                'updated_at' => '2019-08-11 22:58:15',
                'flag' => 1,
            ),
            1 => 
            array (
                'id' => 11,
                'title' => '轮播图11手机端',
                'content' => 'contentcontentcontentcontent',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/370dda0900e14cafccf1520976afdfef.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_370dda0900e14cafccf1520976afdfef.jpg',
                'type' => 1,
                'redirect_url' => '/test/test',
                'activity_id' => NULL,
                'status' => 1,
                'start_time' => '2018-05-05 00:00:00',
                'end_time' => '2019-05-05 00:00:00',
                'sort' => 1,
                'created_at' => '2019-08-06 15:42:13',
                'updated_at' => '2019-08-10 15:23:58',
                'flag' => 2,
            ),
            2 => 
            array (
                'id' => 12,
                'title' => 'SVIP',
                'content' => 'SVIP',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/0dfd325e29bef9e8cb158efba50814bd.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_0dfd325e29bef9e8cb158efba50814bd.jpg',
                'type' => 1,
                'redirect_url' => 'www.baidu.com',
                'activity_id' => NULL,
                'status' => 0,
                'start_time' => '2019-08-11 15:23:42',
                'end_time' => '2019-08-30 15:23:45',
                'sort' => 3,
                'created_at' => '2019-08-10 15:23:53',
                'updated_at' => '2019-08-26 17:58:41',
                'flag' => 1,
            ),
            3 => 
            array (
                'id' => 13,
                'title' => '竞猜世界杯',
                'content' => '竞猜世界杯',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/f705eee648e84f963819cc34a289dfde.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_f705eee648e84f963819cc34a289dfde.jpg',
                'type' => 2,
                'redirect_url' => NULL,
                'activity_id' => 11,
                'status' => 1,
                'start_time' => '2019-08-11 22:56:43',
                'end_time' => '2019-08-11 22:56:45',
                'sort' => 4,
                'created_at' => '2019-08-11 22:56:48',
                'updated_at' => '2019-08-11 22:56:48',
                'flag' => 1,
            ),
            4 => 
            array (
                'id' => 14,
                'title' => '拼手气大翻牌',
                'content' => '拼手气大翻牌',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/ed75d4ab4b9f119c08b60ea5fcadbd0a.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_ed75d4ab4b9f119c08b60ea5fcadbd0a.jpg',
                'type' => 1,
                'redirect_url' => '/index',
                'activity_id' => NULL,
                'status' => 1,
                'start_time' => '2019-08-11 22:57:47',
                'end_time' => '2019-08-11 22:57:49',
                'sort' => 5,
                'created_at' => '2019-08-11 22:57:52',
                'updated_at' => '2019-08-11 22:57:52',
                'flag' => 1,
            ),
            5 => 
            array (
                'id' => 16,
                'title' => '123',
                'content' => '123',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/a9c3da88152e6698058cd5abe7cd2c55.png',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_a9c3da88152e6698058cd5abe7cd2c55.png',
                'type' => 2,
                'redirect_url' => NULL,
                'activity_id' => 12,
                'status' => 1,
                'start_time' => '2019-08-22 18:18:35',
                'end_time' => '2019-08-23 18:18:38',
                'sort' => 6,
                'created_at' => '2019-08-22 18:18:48',
                'updated_at' => '2019-08-22 18:18:48',
                'flag' => 2,
            ),
            6 => 
            array (
                'id' => 17,
                'title' => '1233',
                'content' => '123',
                'pic_path' => '/uploaded_files/aa_1/homepage_banner/7b4d9f2189afa219dd049af26ffad5c3.jpg',
                'thumbnail_path' => '/uploaded_files/aa_1/homepage_banner/sm_7b4d9f2189afa219dd049af26ffad5c3.jpg',
                'type' => 1,
                'redirect_url' => 'www.baidu.com',
                'activity_id' => NULL,
                'status' => 1,
                'start_time' => '2019-08-22 18:22:09',
                'end_time' => '2019-08-23 18:22:11',
                'sort' => 7,
                'created_at' => '2019-08-22 18:22:23',
                'updated_at' => '2019-08-22 18:22:23',
                'flag' => 2,
            ),
        ));
        
        
    }
}