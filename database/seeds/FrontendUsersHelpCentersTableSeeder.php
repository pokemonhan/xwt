<?php

use Illuminate\Database\Seeder;

class FrontendUsersHelpCentersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('frontend_users_help_centers')->delete();
        
        \DB::table('frontend_users_help_centers')->insert(array (
            0 => 
            array (
                'id' => 2,
                'pid' => 1,
                'menu' => '如何注册',
                'content' => '您可通过百度、QQ搜索“春秋 开户”寻找春秋的代理开户。一旦您注册成功，不仅能即刻享受平台的精彩游戏，更能同时成为平台的代理赚取丰厚收益。',
                'status' => 1,
                'created_at' => '2019-07-25 16:13:57',
                'updated_at' => '2019-07-25 16:13:57',
            ),
            1 => 
            array (
                'id' => 4,
                'pid' => 3,
                'menu' => '銀行卡充值',
                'content' => '银行卡充值渠道目前暂时仅对会员等级 VIP 4 及以上等级开放',
                'status' => 1,
                'created_at' => '2019-07-09 16:06:32',
                'updated_at' => '2019-07-09 16:06:31',
            ),
            2 => 
            array (
                'id' => 5,
                'pid' => 3,
                'menu' => '银联扫码充值',
                'content' => '进入充值界面，选择银联扫码，输入充值金额后，点击“下一步”跳转到二维码支付页面

',
                'status' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 7,
                'pid' => 6,
                'menu' => '时时彩',
                'content' => '春秋游戏目前上线了重庆、黑龙江、新疆、天津等省市的时时彩以及春秋自主研发的1分钟开一期奖的春秋时时彩系列（春秋分分彩）。',
                'status' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 8,
                'pid' => 6,
                'menu' => '11选5',
                'content' => '时时彩属于高频彩，投注区分为万位、千位、百位、十位和个位，各位号码范围为0～9，每期从各位上开出1个号码组成中奖号码。玩法即是竞猜5位开奖号码的全部号码、部分号码或部分号码特征。时时彩分星彩玩法和大小单双玩法。',
                'status' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 10,
                'pid' => 9,
                'menu' => '如何兑奖',
                'content' => '在公布开奖号码后30秒内，系统会自动把奖金全部派送到用户的平台账户，用户可进入"我的账户"，在"游戏记录"中查询投注是否中奖及奖金情况。若中奖用户超过5分钟未收到奖金，请联系客服并提供购彩记录请求查询帮助。春秋游戏对奖金不收取任何手续费、佣金或税金。自2016年5月2日起，高频彩奖金限额为  时时彩 40万, 11选5 20万 ，低频彩系列10万  （多开账户视为同一人）',
                'status' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 12,
                'pid' => 11,
                'menu' => '如何提款',
                'content' => '用户须首先绑定银行卡，才可进行提现操作。当投注额满足充值金额的30%后，即可在账户中进行提款操作。平台提款3分钟到账，24小时服务，0手续费，单笔提现最低100元，最高49999元，单日提现次数上限为5次，支持15家银行转账（工行、建行、招行、农行、交行、中行、中信、浦发、民生、邮政、广发、平安、光大、兴业、华夏）。',
                'status' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 17,
                'pid' => 0,
                'menu' => '网上投注',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 16:56:26',
                'updated_at' => '2019-08-13 16:56:26',
            ),
            8 => 
            array (
                'id' => 18,
                'pid' => 0,
                'menu' => '常见问题',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:03:57',
                'updated_at' => '2019-08-13 17:03:57',
            ),
            9 => 
            array (
                'id' => 19,
                'pid' => 0,
                'menu' => '新手指引',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:04:55',
                'updated_at' => '2019-08-13 17:04:55',
            ),
            10 => 
            array (
                'id' => 20,
                'pid' => 18,
                'menu' => '1、如何注册？',
                'content' => '<p>如何注册</p>',
                'status' => 1,
                'created_at' => '2019-08-20 16:40:22',
                'updated_at' => '2019-08-20 16:40:22',
            ),
            11 => 
            array (
                'id' => 21,
                'pid' => 18,
                'menu' => '2、在彩票网站注册要收费吗？',
            'content' => '<p>if(n&gt;=0)</p><p>&nbsp; &nbsp; &nbsp; &nbsp; abs=n;</p><p>&nbsp; &nbsp; else</p><p>&nbsp; &nbsp; &nbsp; &nbsp; abs=-n;</p>',
                'status' => 1,
                'created_at' => '2019-09-03 17:44:35',
                'updated_at' => '2019-09-03 17:44:35',
            ),
            12 => 
            array (
                'id' => 22,
                'pid' => 18,
                'menu' => '3、忘记了登录密码怎么办？',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:06:54',
                'updated_at' => '2019-08-13 17:06:54',
            ),
            13 => 
            array (
                'id' => 23,
                'pid' => 18,
                'menu' => '4、有银行卡就可以在本站充值了吗？',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:07:09',
                'updated_at' => '2019-08-13 17:07:09',
            ),
            14 => 
            array (
                'id' => 24,
                'pid' => 18,
                'menu' => '5、如何开通网上支付？',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:07:24',
                'updated_at' => '2019-08-13 17:07:24',
            ),
            15 => 
            array (
                'id' => 25,
                'pid' => 18,
                'menu' => '6、如何在本站充值？',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:07:48',
                'updated_at' => '2019-08-13 17:07:48',
            ),
            16 => 
            array (
                'id' => 26,
                'pid' => 18,
                'menu' => '7、在本站充值要手续费吗？',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:08:06',
                'updated_at' => '2019-08-13 17:08:06',
            ),
            17 => 
            array (
                'id' => 27,
                'pid' => 18,
                'menu' => '8、如何查看是否中奖？',
                'content' => NULL,
                'status' => 1,
                'created_at' => '2019-08-13 17:08:20',
                'updated_at' => '2019-08-13 17:08:20',
            ),
            18 => 
            array (
                'id' => 28,
                'pid' => 17,
                'menu' => '1、如何查询追号记录？',
                'content' => '<p>test</p>',
                'status' => 1,
                'created_at' => '2019-08-20 16:12:40',
                'updated_at' => '2019-08-20 16:12:40',
            ),
            19 => 
            array (
                'id' => 33,
                'pid' => 32,
                'menu' => '333',
                'content' => '<p>12312312312312312312312312312312312312312312323123231232312323123231232312323123231232312323123231232312323123231232312323123231232312323123231232312323123231232312323123231232312323123</p>',
                'status' => 1,
                'created_at' => '2019-08-19 16:18:03',
                'updated_at' => '2019-08-19 16:18:03',
            ),
        ));
        
        
    }
}