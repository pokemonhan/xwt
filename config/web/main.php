<?php

return [

    // 缓存
    'cache' => [
        //============================彩种相关 BEGIN============================
        'lottery'   => [
            'key'           => 'lottery_all',
            'expire_time'   => 0,
            'name'          => '游戏缓存',
            'tags'          => 'lottery'
        ],

        'lottery_for_frontend' => [
            'key'           => 'lottery_for_frontend',
            'expire_time'   => 0,
            'name'          => '游戏缓存-前端',
            'tags'          => 'lottery'
        ],

        'method_object'     => [
            'key'           => 'lottery_method_object',
            'expire_time'   => 0,
            'name'          => '玩法对象缓存',
            'tags'          => 'lottery'
        ],

        'method_config'   => [
            'key'           => 'lottery_method_config_all',
            'expire_time'   => 0,
            'name'          => '玩法配置缓存',
            'tags'          => 'lottery'
        ],

        'backend_lottery_method_list'    => [
            'key'           => 'backend_lottery_method_list',
            'expire_time'   => 0,
            'name'          => '后台玩法列表缓存',
            'tags'          => 'lottery'
        ],

        'frontend_lottery_lotteryInfo'    => [
            'key'           => 'frontend_lottery_lotteryInfo',
            'expire_time'   => 0,
            'name'          => '前台彩种详情缓存',
            'tags'          => 'lottery'
        ],

        'lottery_popular_lotteries_web'    => [
            'key'           => 'lottery_popular_lotteries_web',
            'expire_time'   => 1440,
            'name'          => 'web端热门彩种缓存',
            'tags'          => 'lottery'
        ],

        'lottery_popular_lotteries_app'    => [
            'key'           => 'lottery_popular_lotteries_app',
            'expire_time'   => 1440,
            'name'          => 'app端热门彩种缓存',
            'tags'          => 'lottery'
        ],

        'lottery_notice_list'    => [
            'key'           => 'lottery_notice_list',
            'expire_time'   => 0,
            'name'          => '前台开奖公告缓存',
            'tags'          => 'lottery'
        ],

        'lottery_method_leve_detail'    => [
            'key'           => 'lottery_method_leve_detail',
            'expire_time'   => 0,
            'name'          => '后台彩种等级列表缓存',
            'tags'          => 'lottery'
        ],

        'lottery_serie_list'    => [
            'key'           => 'lottery_serie_list',
            'expire_time'   => 0,
            'name'          => '后台彩种系列列表缓存',
            'tags'          => 'lottery'
        ],
        //============================彩种相关 END============================
        
        'account_change_type'   => [
            'key'           => 'c_account_change_type',
            'expire_time'   => 0,
            'name'          => '帐变类型缓存'
        ],

        'notice'   => [
            'key'           => 'c_notice',
            'expire_time'   => 0,
            'name'          => '公告列表'
        ],

        'common'    => [
            'key'           => 'c_common',
            'expire_time'   => 3600,
            'name'          => '常规缓存'
        ],

        'cleaned_images'    => [
            'key'           => 'cleaned_images',
            'expire_time'   => 2880,
            'name'          => '定时清理缓存图片',
            'tags'          => 'images'
        ],

        //============================首页相关 BEGIN============================
        'homepage_activity'    => [
            'key'           => 'homepage_activity',
            'expire_time'   => 2880,
            'name'          => '首页活动列表缓存',
            'tags'          => 'homepage'
        ],

        'homepage_banner_web'    => [
            'key'           => 'homepage_banner_web',
            'expire_time'   => 0,
            'name'          => 'web端轮播图缓存',
            'tags'          => 'homepage'
        ],

        'homepage_banner_app'    => [
            'key'           => 'homepage_banner_app',
            'expire_time'   => 0,
            'name'          => 'app端轮播图缓存',
            'tags'          => 'homepage'
        ],

        /*'homepage_customer_service'    => [
            'key'           => 'homepage_customer_service',
            'expire_time'   => 0,
            'name'          => '首页客服链接缓存',
            'tags'          => 'homepage'
        ],

        'homepage_ico'    => [
            'key'           => 'homepage_ico',
            'expire_time'   => 0,
            'name'          => '首页ico缓存',
            'tags'          => 'homepage'
        ],

        'homepage_logo'    => [
            'key'           => 'homepage_logo',
            'expire_time'   => 0,
            'name'          => '首页logo缓存',
            'tags'          => 'homepage'
        ],

        'homepage_qrcode'    => [
            'key'           => 'homepage_qrcode',
            'expire_time'   => 0,
            'name'          => '首页二维码缓存',
            'tags'          => 'homepage'
        ],*/

        'web_basic_content'    => [
            'key'           => 'web_basic_content',
            'expire_time'   => 0,
            'name'          => '首页基本内容缓存',
            'tags'          => 'homepage'
        ],

        'homepage_popular_lotteries'    => [
            'key'           => 'homepage_popular_lotteries',
            'expire_time'   => 0,
            'name'          => '首页热门彩种',
            'tags'          => 'homepage'
        ],

        'homepage_chess_cards'    => [
            'key'           => 'homepage_chess_cards',
            'expire_time'   => 0,
            'name'          => '首页热门棋牌',
            'tags'          => 'homepage'
        ],

        'homepage_e_game'    => [
            'key'           => 'homepage_e_game',
            'expire_time'   => 0,
            'name'          => '首页热门电子',
            'tags'          => 'homepage'
        ],
        //============================首页相关 END============================
        
        'cron_job_open'    => [
            'key'           => 'cron_job_open',
            'expire_time'   => 0,
            'name'          => '开启状态的定时任务缓存',
            'tags'          => 'job'
        ],

        'frontend_web_info'    => [
            'key'           => 'frontend_web_info',
            'expire_time'   => 0,
            'name'          => '前端网站基本信息缓存',
            'tags'          => 'frontend'
        ]
    ],


];
