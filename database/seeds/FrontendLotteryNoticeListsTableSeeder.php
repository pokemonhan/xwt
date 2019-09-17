<?php

use Illuminate\Database\Seeder;

class FrontendLotteryNoticeListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_lottery_notice_lists')->delete();
        
        \DB::table('frontend_lottery_notice_lists')->insert(array (
            0 => 
            array (
                'id' => 2,
                'lotteries_id' => 'cqssc',
                'cn_name' => '重庆时时彩',
                'status' => 1,
                'sort' => 4,
                'created_at' => '2019-07-23 21:56:29',
                'updated_at' => '2019-08-19 10:53:43',
            ),
            1 => 
            array (
                'id' => 3,
                'lotteries_id' => 'zx1fc',
                'cn_name' => '中兴1分彩',
                'status' => 1,
                'sort' => 2,
                'created_at' => '2019-07-23 21:57:11',
                'updated_at' => '2019-07-27 19:27:03',
            ),
            2 => 
            array (
                'id' => 4,
                'lotteries_id' => 'sd115',
                'cn_name' => '山东11选5',
                'status' => 1,
                'sort' => 5,
                'created_at' => '2019-07-23 21:57:25',
                'updated_at' => '2019-08-19 10:53:43',
            ),
            3 => 
            array (
                'id' => 5,
                'lotteries_id' => 'zx115',
                'cn_name' => '中兴11选5',
                'status' => 1,
                'sort' => 1,
                'created_at' => '2019-07-27 18:37:28',
                'updated_at' => '2019-09-03 17:27:00',
            ),
            4 => 
            array (
                'id' => 6,
                'lotteries_id' => 'hljssc',
                'cn_name' => '黑龙江时时彩',
                'status' => 1,
                'sort' => 6,
                'created_at' => '2019-08-02 16:03:55',
                'updated_at' => '2019-08-19 10:53:40',
            ),
            5 => 
            array (
                'id' => 7,
                'lotteries_id' => 'sx115',
                'cn_name' => '山西11选5',
                'status' => 1,
                'sort' => 3,
                'created_at' => '2019-08-02 16:06:40',
                'updated_at' => '2019-08-19 10:53:40',
            ),
            6 => 
            array (
                'id' => 8,
                'lotteries_id' => 'xjssc',
                'cn_name' => '新疆时时彩',
                'status' => 1,
                'sort' => 7,
                'created_at' => '2019-08-11 17:13:42',
                'updated_at' => '2019-08-11 17:13:42',
            ),
            7 => 
            array (
                'id' => 9,
                'lotteries_id' => 'txffc',
                'cn_name' => '腾讯分分彩',
                'status' => 1,
                'sort' => 8,
                'created_at' => '2019-08-11 17:13:52',
                'updated_at' => '2019-08-11 17:13:52',
            ),
            8 => 
            array (
                'id' => 10,
                'lotteries_id' => 'gd115',
                'cn_name' => '广东11选5',
                'status' => 1,
                'sort' => 9,
                'created_at' => '2019-08-11 17:14:00',
                'updated_at' => '2019-08-11 17:14:00',
            ),
            9 => 
            array (
                'id' => 11,
                'lotteries_id' => 'sh115',
                'cn_name' => '上海11选5',
                'status' => 1,
                'sort' => 10,
                'created_at' => '2019-08-11 17:14:06',
                'updated_at' => '2019-08-11 17:14:06',
            ),
            10 => 
            array (
                'id' => 12,
                'lotteries_id' => 'jx115',
                'cn_name' => '江西11选5',
                'status' => 1,
                'sort' => 11,
                'created_at' => '2019-08-11 17:14:13',
                'updated_at' => '2019-08-13 16:09:40',
            ),
            11 => 
            array (
                'id' => 13,
                'lotteries_id' => 'jsk3',
                'cn_name' => '江苏快3',
                'status' => 1,
                'sort' => 12,
                'created_at' => '2019-08-11 17:14:27',
                'updated_at' => '2019-08-11 17:14:27',
            ),
            12 => 
            array (
                'id' => 14,
                'lotteries_id' => 'ahk3',
                'cn_name' => '安徽快3',
                'status' => 1,
                'sort' => 13,
                'created_at' => '2019-08-11 17:14:33',
                'updated_at' => '2019-08-11 17:14:33',
            ),
            13 => 
            array (
                'id' => 15,
                'lotteries_id' => 'gsk3',
                'cn_name' => '甘肃快3',
                'status' => 1,
                'sort' => 14,
                'created_at' => '2019-08-11 17:14:40',
                'updated_at' => '2019-08-11 17:14:40',
            ),
            14 => 
            array (
                'id' => 16,
                'lotteries_id' => 'hnk3',
                'cn_name' => '河南快3',
                'status' => 1,
                'sort' => 15,
                'created_at' => '2019-08-11 17:14:47',
                'updated_at' => '2019-08-13 16:09:39',
            ),
            15 => 
            array (
                'id' => 17,
                'lotteries_id' => 'zxk3',
                'cn_name' => '中兴快3',
                'status' => 1,
                'sort' => 16,
                'created_at' => '2019-08-11 17:14:55',
                'updated_at' => '2019-08-11 17:14:55',
            ),
            16 => 
            array (
                'id' => 18,
                'lotteries_id' => 'fc3d',
                'cn_name' => '福彩3D',
                'status' => 1,
                'sort' => 17,
                'created_at' => '2019-08-11 17:15:03',
                'updated_at' => '2019-08-11 17:15:03',
            ),
            17 => 
            array (
                'id' => 19,
                'lotteries_id' => 'zx3d',
                'cn_name' => '中兴3D',
                'status' => 1,
                'sort' => 18,
                'created_at' => '2019-08-11 17:15:09',
                'updated_at' => '2019-08-11 17:15:09',
            ),
            18 => 
            array (
                'id' => 20,
                'lotteries_id' => 'shssl',
                'cn_name' => '上海时时乐',
                'status' => 1,
                'sort' => 19,
                'created_at' => '2019-08-11 17:15:15',
                'updated_at' => '2019-08-11 17:15:15',
            ),
            19 => 
            array (
                'id' => 21,
                'lotteries_id' => 'p3p5',
                'cn_name' => '排列35',
                'status' => 1,
                'sort' => 20,
                'created_at' => '2019-08-11 17:15:23',
                'updated_at' => '2019-08-11 17:15:23',
            ),
            20 => 
            array (
                'id' => 22,
                'lotteries_id' => 'bjpk10',
                'cn_name' => '北京PK10',
                'status' => 1,
                'sort' => 21,
                'created_at' => '2019-08-11 17:15:30',
                'updated_at' => '2019-08-11 17:15:30',
            ),
            21 => 
            array (
                'id' => 23,
                'lotteries_id' => 'ftpk10',
                'cn_name' => '急速飞艇',
                'status' => 1,
                'sort' => 22,
                'created_at' => '2019-08-11 17:15:35',
                'updated_at' => '2019-08-13 16:09:42',
            ),
            22 => 
            array (
                'id' => 24,
                'lotteries_id' => 'zxpk10',
                'cn_name' => '中兴PK10',
                'status' => 1,
                'sort' => 23,
                'created_at' => '2019-08-11 17:15:48',
                'updated_at' => '2019-08-13 16:09:43',
            ),
            23 => 
            array (
                'id' => 25,
                'lotteries_id' => 'hklhc',
                'cn_name' => '香港六合彩',
                'status' => 1,
                'sort' => 24,
                'created_at' => '2019-08-11 17:15:57',
                'updated_at' => '2019-08-13 16:09:44',
            ),
            24 => 
            array (
                'id' => 26,
                'lotteries_id' => 'ah115',
                'cn_name' => '安徽11选5',
                'status' => 1,
                'sort' => 25,
                'created_at' => '2019-08-11 17:16:11',
                'updated_at' => '2019-08-13 16:09:44',
            ),
            25 => 
            array (
                'id' => 27,
                'lotteries_id' => 'tx05fc',
                'cn_name' => '腾讯5分彩',
                'status' => 1,
                'sort' => 26,
                'created_at' => '2019-08-11 17:16:16',
                'updated_at' => '2019-08-11 17:16:16',
            ),
            26 => 
            array (
                'id' => 28,
                'lotteries_id' => 'tx10fc',
                'cn_name' => '腾讯10分彩',
                'status' => 1,
                'sort' => 27,
                'created_at' => '2019-08-11 17:16:22',
                'updated_at' => '2019-08-11 17:16:22',
            ),
            27 => 
            array (
                'id' => 29,
                'lotteries_id' => 'qiqtxffc',
                'cn_name' => '奇趣腾讯分分彩',
                'status' => 1,
                'sort' => 28,
                'created_at' => '2019-08-11 17:16:28',
                'updated_at' => '2019-08-11 17:16:28',
            ),
            28 => 
            array (
                'id' => 30,
                'lotteries_id' => 'duotxffc',
                'cn_name' => '多彩腾讯分分彩',
                'status' => 1,
                'sort' => 29,
                'created_at' => '2019-08-11 17:16:34',
                'updated_at' => '2019-08-11 17:16:34',
            ),
            29 => 
            array (
                'id' => 31,
                'lotteries_id' => 'wb05fc',
                'cn_name' => '微博5分彩',
                'status' => 1,
                'sort' => 30,
                'created_at' => '2019-08-11 17:16:41',
                'updated_at' => '2019-08-11 17:16:41',
            ),
            30 => 
            array (
                'id' => 32,
                'lotteries_id' => 'heb115',
                'cn_name' => '河北11选5',
                'status' => 0,
                'sort' => 31,
                'created_at' => '2019-08-11 17:16:46',
                'updated_at' => '2019-08-12 19:38:22',
            ),
            31 => 
            array (
                'id' => 33,
                'lotteries_id' => 'hb115',
                'cn_name' => '湖北11选5',
                'status' => 0,
                'sort' => 32,
                'created_at' => '2019-08-11 17:17:00',
                'updated_at' => '2019-08-12 19:38:23',
            ),
            32 => 
            array (
                'id' => 34,
                'lotteries_id' => 'shx115',
                'cn_name' => '陕西11选5',
                'status' => 0,
                'sort' => 33,
                'created_at' => '2019-08-11 17:17:06',
                'updated_at' => '2019-08-12 19:38:23',
            ),
            33 => 
            array (
                'id' => 35,
                'lotteries_id' => 'hbk3',
                'cn_name' => '湖北快3',
                'status' => 0,
                'sort' => 34,
                'created_at' => '2019-08-11 17:17:13',
                'updated_at' => '2019-08-12 19:38:28',
            ),
            34 => 
            array (
                'id' => 36,
                'lotteries_id' => 'nmgk3',
                'cn_name' => '内蒙古快3',
                'status' => 0,
                'sort' => 35,
                'created_at' => '2019-08-11 17:17:23',
                'updated_at' => '2019-08-12 19:38:26',
            ),
            35 => 
            array (
                'id' => 37,
                'lotteries_id' => 'jxk3',
                'cn_name' => '江西快3',
                'status' => 0,
                'sort' => 36,
                'created_at' => '2019-08-11 17:17:28',
                'updated_at' => '2019-08-12 19:36:52',
            ),
            36 => 
            array (
                'id' => 38,
                'lotteries_id' => 'alyffc',
                'cn_name' => '阿里云分分彩',
                'status' => 1,
                'sort' => 37,
                'created_at' => '2019-09-03 17:22:16',
                'updated_at' => '2019-09-03 17:22:16',
            ),
        ));
        
        
    }
}