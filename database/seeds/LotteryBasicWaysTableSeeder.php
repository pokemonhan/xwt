<?php

use Illuminate\Database\Seeder;

class LotteryBasicWaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lottery_basic_ways')->delete();
        
        \DB::table('lottery_basic_ways')->insert(array (
            0 => 
            array (
                'id' => 1,
                'lottery_type' => 1,
                'name' => '单式',
                'function' => 'Enum',
                'description' => '枚举单注号码的方式',
                'sequence' => NULL,
                'created_at' => '2014-07-11 04:46:50',
                'updated_at' => '2015-06-06 17:29:57',
            ),
            1 => 
            array (
                'id' => 2,
                'lottery_type' => 1,
                'name' => '复式',
                'function' => 'Constituted',
                'description' => '通过指定一组数字，购买所有由这组数字组成的单注',
                'sequence' => NULL,
                'created_at' => '2014-07-11 04:48:05',
                'updated_at' => '2015-06-06 17:29:38',
            ),
            2 => 
            array (
                'id' => 3,
                'lottery_type' => 1,
                'name' => '包胆',
                'function' => 'Necessary',
                'description' => '通过指定一个数字，购买包括这个数字的所有单注',
                'sequence' => NULL,
                'created_at' => '2014-07-11 04:48:42',
                'updated_at' => '2014-07-21 17:54:06',
            ),
            3 => 
            array (
                'id' => 4,
                'lottery_type' => 1,
                'name' => '和值',
                'function' => 'Sum',
                'description' => '指定一个数字，购买各位数字总和等于这个数字的所有单注',
                'sequence' => NULL,
                'created_at' => '2014-07-11 04:49:22',
                'updated_at' => '2014-07-21 17:56:05',
            ),
            4 => 
            array (
                'id' => 5,
                'lottery_type' => 1,
                'name' => '跨度',
                'function' => 'Span',
                'description' => '指定一个数字，购买最大数字与最小数字的差值为这个数字的所有单注',
                'sequence' => NULL,
                'created_at' => '2014-07-11 04:50:53',
                'updated_at' => '2014-07-21 17:55:58',
            ),
            5 => 
            array (
                'id' => 6,
                'lottery_type' => 1,
                'name' => '和尾',
                'function' => 'SumTail',
                'description' => '专用于和值尾数玩法',
                'sequence' => NULL,
                'created_at' => '2014-07-11 04:51:54',
                'updated_at' => '2014-08-13 20:29:09',
            ),
            6 => 
            array (
                'id' => 7,
                'lottery_type' => 1,
                'name' => '直选组合',
                'function' => 'MultiSequencing',
                'description' => '通过一个注单购买多种玩法的方式，如三星组合',
                'sequence' => NULL,
                'created_at' => '2014-07-13 21:20:50',
                'updated_at' => '2014-07-21 17:54:44',
            ),
            7 => 
            array (
                'id' => 8,
                'lottery_type' => 1,
                'name' => '混合组选',
                'function' => 'MixCombin',
                'description' => '用于在一张注单中用于购买多种组选玩法的方式，用于三星组三和组六的混合购买，属单式',
                'sequence' => NULL,
                'created_at' => '2014-07-13 21:22:53',
                'updated_at' => '2014-07-21 17:55:27',
            ),
            8 => 
            array (
                'id' => 9,
                'lottery_type' => 1,
                'name' => '定位复式',
                'function' => 'SeparatedConstituted',
                'description' => '用于直选等分位置选号的复式投注',
                'sequence' => NULL,
                'created_at' => '2014-07-18 21:50:24',
                'updated_at' => '2015-06-06 17:29:28',
            ),
            9 => 
            array (
                'id' => 10,
                'lottery_type' => 1,
                'name' => '趣味定位复式',
                'function' => 'FunSeparatedConstituted',
                'description' => '直选定位复式的特殊形态，即前一位或前两位购买的是大小模式，其余位购买的是数字本身',
                'sequence' => NULL,
                'created_at' => '2014-08-08 00:34:45',
                'updated_at' => '2014-08-08 00:34:45',
            ),
            10 => 
            array (
                'id' => 11,
                'lottery_type' => 1,
                'name' => '区间定位复式',
                'function' => 'SectionalizedSeparatedConstituted',
                'description' => '直选定位复式的特殊形态，即前一位或前两位购买的是分区模式（0－4），其余位购买的是数字本身',
                'sequence' => NULL,
                'created_at' => '2014-08-08 00:37:33',
                'updated_at' => '2014-08-08 02:01:04',
            ),
            11 => 
            array (
                'id' => 12,
                'lottery_type' => 1,
                'name' => '特殊组合复式',
                'function' => 'SpecialConstituted',
                'description' => '专用于三星特殊玩法，此类玩法是购买形态而非号码本身（数字代表的是形态）',
                'sequence' => NULL,
                'created_at' => '2014-08-12 22:31:10',
                'updated_at' => '2014-08-12 22:31:10',
            ),
            12 => 
            array (
                'id' => 13,
                'lottery_type' => 1,
                'name' => '定位胆组合',
                'function' => 'MultiOne',
                'description' => '专用于一星玩法的投注方式：一次购买所关联的各个一星玩法',
                'sequence' => NULL,
                'created_at' => '2014-08-13 20:28:55',
                'updated_at' => '2014-08-13 20:28:55',
            ),
            13 => 
            array (
                'id' => 14,
                'lottery_type' => 1,
                'name' => '大小单双',
                'function' => 'BigSmallOddEven',
                'description' => '专用于大小单双玩法的投注方式',
                'sequence' => NULL,
                'created_at' => '2014-08-13 21:10:18',
                'updated_at' => '2014-08-13 21:10:18',
            ),
            14 => 
            array (
                'id' => 15,
                'lottery_type' => 1,
                'name' => '五星组选30复式',
                'function' => 'ConstitutedForCombin30',
                'description' => '专用于五星组选30的投注方式',
                'sequence' => NULL,
                'created_at' => '2014-08-26 01:11:22',
                'updated_at' => '2014-08-26 01:11:22',
            ),
            15 => 
            array (
                'id' => 16,
                'lottery_type' => 1,
                'name' => '双区组选复式',
                'function' => 'ConstitutedDoubleArea',
                'description' => '用于需要2个选区的组选复式，如五星组选5，10，20等，但不含五星组选30',
                'sequence' => NULL,
                'created_at' => '2014-08-26 01:21:02',
                'updated_at' => '2014-08-26 01:21:02',
            ),
            16 => 
            array (
                'id' => 17,
                'lottery_type' => 2,
                'name' => '单式',
                'function' => 'LottoEqual',
                'description' => '枚举单注号码的方式',
                'sequence' => NULL,
                'created_at' => '2014-09-05 00:46:49',
                'updated_at' => '2014-09-05 00:46:49',
            ),
            17 => 
            array (
                'id' => 18,
                'lottery_type' => 2,
                'name' => '复式',
                'function' => 'LottoConstituted',
                'description' => '通过指定一组数字，购买所有由这组数字组成的单注',
                'sequence' => NULL,
                'created_at' => '2014-09-05 00:47:53',
                'updated_at' => '2014-09-05 00:47:53',
            ),
            18 => 
            array (
                'id' => 19,
                'lottery_type' => 2,
                'name' => '定位复式',
                'function' => 'LottoSeparatedConstituted',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2014-09-05 00:48:40',
                'updated_at' => '2014-09-05 00:48:40',
            ),
            19 => 
            array (
                'id' => 20,
                'lottery_type' => 2,
                'name' => '胆拖',
                'function' => 'LottoNecessaryConstituted',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2014-09-05 02:33:54',
                'updated_at' => '2014-09-05 02:34:04',
            ),
            20 => 
            array (
                'id' => 21,
                'lottery_type' => 2,
                'name' => '定位胆组合',
                'function' => 'LottoMultiOne',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2014-09-05 19:50:36',
                'updated_at' => '2014-09-05 19:50:36',
            ),
            21 => 
            array (
                'id' => 22,
                'lottery_type' => 1,
                'name' => '二星大小',
                'function' => 'TwoStarBigSmall',
                'description' => '专用于万千、万百、万十等的两两比较',
                'sequence' => NULL,
                'created_at' => '2015-10-06 17:42:37',
                'updated_at' => '2015-10-06 17:42:37',
            ),
            22 => 
            array (
                'id' => 23,
                'lottery_type' => 1,
                'name' => '二星特殊',
                'function' => 'TwoStarSpecial',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-02-05 22:04:01',
                'updated_at' => '2016-02-05 22:04:01',
            ),
            23 => 
            array (
                'id' => 25,
                'lottery_type' => 1,
                'name' => 'PK10 大小单双',
                'function' => 'PkBigSmallOddEven',
                'description' => 'PK10大小单双',
                'sequence' => NULL,
                'created_at' => '2016-04-15 00:39:09',
                'updated_at' => '2016-04-22 22:32:48',
            ),
            24 => 
            array (
                'id' => 26,
                'lottery_type' => 1,
                'name' => 'PK10龙虎',
                'function' => 'PkDragonwithtiger',
                'description' => 'PK10龙虎',
                'sequence' => NULL,
                'created_at' => '2016-04-21 19:08:52',
                'updated_at' => '2016-04-28 23:59:27',
            ),
            25 => 
            array (
                'id' => 27,
                'lottery_type' => 1,
                'name' => 'PK10直选复试',
                'function' => 'PkSeparatedConstituted',
                'description' => 'PK10直选复式',
                'sequence' => NULL,
                'created_at' => '2016-04-23 21:56:34',
                'updated_at' => '2016-04-23 21:56:34',
            ),
            26 => 
            array (
                'id' => 28,
                'lottery_type' => 1,
                'name' => '快乐28串关',
                'function' => 'Multiple',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-04-26 10:34:36',
                'updated_at' => '2016-06-28 16:09:09',
            ),
            27 => 
            array (
                'id' => 29,
                'lottery_type' => 1,
                'name' => '快乐28两极',
                'function' => 'Extremum',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-04-26 10:35:33',
                'updated_at' => '2016-06-28 16:08:52',
            ),
            28 => 
            array (
                'id' => 30,
                'lottery_type' => 1,
                'name' => 'PK10定位胆复式',
                'function' => 'Pkconstituted',
                'description' => 'Pk10定位胆',
                'sequence' => NULL,
                'created_at' => '2016-04-29 00:48:21',
                'updated_at' => '2016-05-02 18:34:17',
            ),
            29 => 
            array (
                'id' => 31,
                'lottery_type' => 1,
                'name' => '快乐28大小',
                'function' => 'BigSmall',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-05-02 14:41:23',
                'updated_at' => '2016-06-28 16:09:33',
            ),
            30 => 
            array (
                'id' => 32,
                'lottery_type' => 1,
                'name' => '快乐28单双',
                'function' => 'OddEven',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-05-02 14:41:45',
                'updated_at' => '2016-06-28 16:09:23',
            ),
            31 => 
            array (
                'id' => 33,
                'lottery_type' => 1,
                'name' => '六合彩特码',
                'function' => 'Sixtema',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:46:41',
                'updated_at' => '2016-11-09 23:47:13',
            ),
            32 => 
            array (
                'id' => 34,
                'lottery_type' => 1,
                'name' => '六合彩正码',
                'function' => 'Sixzhengma',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:47:40',
                'updated_at' => '2016-11-09 23:47:40',
            ),
            33 => 
            array (
                'id' => 35,
                'lottery_type' => 1,
                'name' => '六合彩半波',
                'function' => 'Sixbanbo',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:47:59',
                'updated_at' => '2016-11-09 23:47:59',
            ),
            34 => 
            array (
                'id' => 36,
                'lottery_type' => 1,
                'name' => '六合彩特肖',
                'function' => 'Sixtexiao',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:48:25',
                'updated_at' => '2016-11-11 01:44:24',
            ),
            35 => 
            array (
                'id' => 37,
                'lottery_type' => 1,
                'name' => '六合彩尾数',
                'function' => 'Sixweishu',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:48:47',
                'updated_at' => '2016-11-09 23:48:47',
            ),
            36 => 
            array (
                'id' => 38,
                'lottery_type' => 1,
                'name' => '六合彩总分',
                'function' => 'Sixzongfen',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:50:22',
                'updated_at' => '2016-11-09 23:50:22',
            ),
            37 => 
            array (
                'id' => 39,
                'lottery_type' => 1,
                'name' => '六合彩不中',
                'function' => 'Sixbuzhong',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-09 23:50:39',
                'updated_at' => '2016-11-09 23:50:39',
            ),
            38 => 
            array (
                'id' => 40,
                'lottery_type' => 1,
                'name' => '六合彩1肖',
                'function' => 'Sixyixiao',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-11 01:44:42',
                'updated_at' => '2016-11-11 01:44:42',
            ),
            39 => 
            array (
                'id' => 41,
                'lottery_type' => 1,
                'name' => '六合彩六肖',
                'function' => 'Sixliuxiao',
                'description' => '',
                'sequence' => NULL,
                'created_at' => '2016-11-11 01:45:07',
                'updated_at' => '2016-11-11 01:45:07',
            ),
            40 => 
            array (
                'id' => 42,
                'lottery_type' => 2,
                'name' => '定位胆乐透',
                'function' => 'LottoSingleOne',
                'description' => '定位胆第一位,定位胆第二位
定位胆第一位,定位胆第二位,定位胆第三位',
                'sequence' => NULL,
                'created_at' => '2014-09-05 19:50:36',
                'updated_at' => '2014-09-05 19:50:36',
            ),
        ));
        
        
    }
}