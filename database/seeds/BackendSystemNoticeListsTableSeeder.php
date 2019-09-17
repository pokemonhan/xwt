<?php

use Illuminate\Database\Seeder;

class BackendSystemNoticeListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('backend_system_notice_lists')->delete();
        
        \DB::table('backend_system_notice_lists')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 1,
                'message' => '落班饮啤酒了喂',
                'created_at' => '2019-06-03 11:00:37',
                'updated_at' => '2019-06-03 11:00:37',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 1,
                'message' => '落班饮啤酒了喂',
                'created_at' => '2019-06-03 11:02:26',
                'updated_at' => '2019-06-03 11:02:26',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 1,
                'message' => '落班饮啤酒了喂',
                'created_at' => '2019-06-03 11:03:25',
                'updated_at' => '2019-06-03 11:03:25',
            ),
            3 => 
            array (
                'id' => 6,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-06-03 14:56:53',
                'updated_at' => '2019-06-03 14:56:53',
            ),
            4 => 
            array (
                'id' => 11,
                'type' => 2,
                'message' => '你的人工充值申请已通过',
                'created_at' => '2019-06-03 15:33:42',
                'updated_at' => '2019-06-03 15:33:42',
            ),
            5 => 
            array (
                'id' => 12,
                'type' => 2,
                'message' => '你的人工充值申请被驳回',
                'created_at' => '2019-06-03 15:36:28',
                'updated_at' => '2019-06-03 15:36:28',
            ),
            6 => 
            array (
                'id' => 22,
                'type' => 2,
                'message' => '有新的人工充值需要审核',
                'created_at' => '2019-06-03 16:07:00',
                'updated_at' => '2019-06-03 16:07:00',
            ),
            7 => 
            array (
                'id' => 23,
                'type' => 2,
                'message' => '有新的人工充值需要审核',
                'created_at' => '2019-06-03 18:14:01',
                'updated_at' => '2019-06-03 18:14:01',
            ),
            8 => 
            array (
                'id' => 24,
                'type' => 2,
                'message' => '有新的人工充值需要审核',
                'created_at' => '2019-06-05 11:42:22',
                'updated_at' => '2019-06-05 11:42:22',
            ),
            9 => 
            array (
                'id' => 25,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-07-12 17:08:55',
                'updated_at' => '2019-07-12 17:08:55',
            ),
            10 => 
            array (
                'id' => 26,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-07-12 17:09:20',
                'updated_at' => '2019-07-12 17:09:20',
            ),
            11 => 
            array (
                'id' => 27,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-07-12 17:11:11',
                'updated_at' => '2019-07-12 17:11:11',
            ),
            12 => 
            array (
                'id' => 28,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-07-12 18:05:35',
                'updated_at' => '2019-07-12 18:05:35',
            ),
            13 => 
            array (
                'id' => 29,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-08-13 16:20:18',
                'updated_at' => '2019-08-13 16:20:18',
            ),
            14 => 
            array (
                'id' => 30,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-08-13 16:43:22',
                'updated_at' => '2019-08-13 16:43:22',
            ),
            15 => 
            array (
                'id' => 31,
                'type' => 2,
                'message' => '有新的文章需要审核',
                'created_at' => '2019-08-13 21:07:06',
                'updated_at' => '2019-08-13 21:07:06',
            ),
        ));
        
        
    }
}