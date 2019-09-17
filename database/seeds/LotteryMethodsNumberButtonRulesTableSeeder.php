<?php

use Illuminate\Database\Seeder;

class LotteryMethodsNumberButtonRulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lottery_methods_number_button_rules')->delete();
        
        \DB::table('lottery_methods_number_button_rules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'digitalCodesTpl',
                'value' => '[0, 1, 2, 3, 4, 5, 6, 7, 8, 9]',
                'status' => 0,
                'created_at' => '2019-07-08 16:54:56',
                'updated_at' => '2019-07-08 16:54:56',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'digitalPosTpl',
                'value' => '["全", "大", "小", "奇", "偶", "清"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:44:40',
                'updated_at' => '2019-07-08 16:44:40',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'digitalZxhzTpl',
                'value' => '[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27]',
                'status' => 0,
                'created_at' => '2019-07-08 16:44:45',
                'updated_at' => '2019-07-08 16:44:45',
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'digitalZuhzTpl',
                'value' => '[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:03',
                'updated_at' => '2019-07-08 16:45:03',
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'digitalZxhz2Tpl',
                'value' => '[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:15',
                'updated_at' => '2019-07-08 16:45:15',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'digitalZuhz2Tpl',
                'value' => '[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:18',
                'updated_at' => '2019-07-08 16:45:18',
            ),
            6 => 
            array (
                'id' => 7,
                'type' => 'digitalDxdsTpl',
                'value' => '["大", "小", "单", "双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:26',
                'updated_at' => '2019-07-08 16:45:26',
            ),
            7 => 
            array (
                'id' => 8,
                'type' => 'digitalLhhTpl',
                'value' => '["龙", "虎", "和"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:34',
                'updated_at' => '2019-07-08 16:45:34',
            ),
            8 => 
            array (
                'id' => 9,
                'type' => 'digitatemaTpl',
                'value' => '["豹子", "顺子", "对子"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:41',
                'updated_at' => '2019-07-08 16:45:41',
            ),
            9 => 
            array (
                'id' => 10,
                'type' => 'lottoCodesTpl',
                'value' => '["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:45:59',
                'updated_at' => '2019-07-08 16:45:59',
            ),
            10 => 
            array (
                'id' => 11,
                'type' => 'pk10CodesTpl',
                'value' => '["01", "02", "03", "04", "05", "06", "07", "08", "09", "10"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:46:15',
                'updated_at' => '2019-07-08 16:46:15',
            ),
            11 => 
            array (
                'id' => 12,
                'type' => 'lhcCodesTpl',
                'value' => '[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49]',
                'status' => 0,
                'created_at' => '2019-07-08 16:46:24',
                'updated_at' => '2019-07-08 16:46:24',
            ),
            12 => 
            array (
                'id' => 13,
                'type' => 'lhcPosTpl_red',
                'value' => '["红大", "红小", "红单", "红双", "红合单", "红合双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:46:41',
                'updated_at' => '2019-07-08 16:46:41',
            ),
            13 => 
            array (
                'id' => 14,
                'type' => 'lhcPosTpl_green',
                'value' => '["绿大", "绿小", "绿单", "绿双", "绿合单", "绿合双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:46:49',
                'updated_at' => '2019-07-08 16:46:49',
            ),
            14 => 
            array (
                'id' => 15,
                'type' => 'lhcPosTpl_blue',
                'value' => '["蓝大", "蓝小", "蓝单", "蓝双", "蓝合单", "蓝合双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:46:56',
                'updated_at' => '2019-07-08 16:46:56',
            ),
            15 => 
            array (
                'id' => 16,
                'type' => 'lhcPosTpl_sub',
                'value' => '["大", "小", "单", "双", "鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊", "猴", "鸡", "狗", "猪"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:47:15',
                'updated_at' => '2019-07-08 16:47:15',
            ),
            16 => 
            array (
                'id' => 17,
                'type' => 'lhcBbCodesTpl',
                'value' => '["红大", "红小", "红单", "红双", "红合单", "红合双", "绿大", "绿小", "绿单", "绿双", "绿合单", "绿合双", "蓝大", "蓝小", "蓝单", "蓝双", "蓝合单", "蓝合双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:47:25',
                'updated_at' => '2019-07-08 16:47:25',
            ),
            17 => 
            array (
                'id' => 18,
                'type' => 'lhcBbPosTpl_wave',
                'value' => '["红波", "绿波", "蓝波"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:47:32',
                'updated_at' => '2019-07-08 16:47:32',
            ),
            18 => 
            array (
                'id' => 19,
                'type' => 'lhcBbPosTpl_sub',
                'value' => '["大", "小", "单", "双", "合单", "合双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:47:41',
                'updated_at' => '2019-07-08 16:47:41',
            ),
            19 => 
            array (
                'id' => 20,
                'type' => 'lhcTxCodesTpl',
                'value' => '["鼠", "马", "牛", "羊", "虎", "猴", "兔", "鸡", "龙", "狗", "蛇", "猪"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:47:44',
                'updated_at' => '2019-07-08 16:47:44',
            ),
            20 => 
            array (
                'id' => 21,
                'type' => 'lhcTxPosTpl_class',
            'value' => '["大肖", "小肖", "男肖", "女肖(五官肖)"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:48:04',
                'updated_at' => '2019-07-08 16:48:04',
            ),
            21 => 
            array (
                'id' => 22,
                'type' => 'lhcTxPosTpl_alias',
                'value' => '["吉美生肖", "凶丑生肖", "野外六兽", "家内六畜", "阳性生肖", "阴性生肖"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:48:12',
                'updated_at' => '2019-07-08 16:48:12',
            ),
            22 => 
            array (
                'id' => 23,
                'type' => 'lhcTxPosTpl_fiveElements',
                'value' => '["五行属金: 猴 鸡", "五行属木: 虎 兔", "五行属水: 鼠 猪", "五行属火: 蛇 马", "五行属土: 牛 龙 羊 狗"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:48:22',
                'updated_at' => '2019-07-08 16:48:22',
            ),
            23 => 
            array (
                'id' => 24,
                'type' => 'lhcZsCodesTpl',
                'value' => '["大", "小", "单", "双", "大单", "大双", "小单", "小双"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:49:07',
                'updated_at' => '2019-07-08 16:49:07',
            ),
            24 => 
            array (
                'id' => 25,
                'type' => 'pcDxdsCodesTpl',
                'value' => '["大", "小", "单", "双", "大单", "大双", "小单", "小双", "极大", "极小", "豹子", "红波", "蓝波", "绿波"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:49:17',
                'updated_at' => '2019-07-08 16:49:17',
            ),
            25 => 
            array (
                'id' => 26,
                'type' => 'pcBZCodesTpl',
                'value' => '["豹子"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:49:23',
                'updated_at' => '2019-07-08 16:49:23',
            ),
            26 => 
            array (
                'id' => 27,
                'type' => 'pcBoCodesTpl',
                'value' => '["红波", "蓝波", "绿波"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:49:30',
                'updated_at' => '2019-07-08 16:49:30',
            ),
            27 => 
            array (
                'id' => 28,
                'type' => 'pcYlcCodesTpl',
                'value' => '["大", "小", "单", "双", 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]',
                'status' => 0,
                'created_at' => '2019-07-08 16:49:43',
                'updated_at' => '2019-07-08 16:49:43',
            ),
            28 => 
            array (
                'id' => 29,
                'type' => 'pcLhdCodesTpl_w',
                'value' => '["wq", "wb", "ws", "wg"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:49:52',
                'updated_at' => '2019-07-08 16:49:52',
            ),
            29 => 
            array (
                'id' => 30,
                'type' => 'pcLhdCodesTpl_q',
                'value' => '["qb", "qs", "qg"]',
                'status' => 0,
                'created_at' => '2019-07-08 16:50:02',
                'updated_at' => '2019-07-08 16:50:02',
            ),
            30 => 
            array (
                'id' => 31,
                'type' => 'DTYS',
                'value' => '[6, 5, 4, 3, 2, 1]',
                'status' => 0,
                'created_at' => '2019-07-08 16:00:30',
                'updated_at' => '2019-07-08 16:00:30',
            ),
            31 => 
            array (
                'id' => 32,
                'type' => 'EBTH',
                'value' => '[12, 13, 14, 15, 16, 23, 24, 25, 26, 34, 35, 36, 45, 46, 56]',
                'status' => 0,
                'created_at' => '2019-07-08 16:00:17',
                'updated_at' => '2019-07-08 16:00:17',
            ),
        ));
        
        
    }
}