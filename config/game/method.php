<?php

return [
    'pre_process_method' => [
        'ssc' => [
            'WXZU120' => ['w', 'q', 'b', 's', 'g'],
            'WXZU60' => ['w', 'q', 'b', 's', 'g'],
            'WXZU30' => ['w', 'q', 'b', 's', 'g'],
            'WXZU20' => ['w', 'q', 'b', 's', 'g'],
            'WXZU10' => ['w', 'q', 'b', 's', 'g'],
            'WXZU5' => ['w', 'q', 'b', 's', 'g'],

            'HSCS' => ['w', 'q', 'b', 's', 'g'],
            'SXBX' => ['w', 'q', 'b', 's', 'g'],
            'SJFC' => ['w', 'q', 'b', 's', 'g'],

            'SXZU24' => ['q', 'b', 's', 'g'],
            'SXZU12' => ['q', 'b', 's', 'g'],
            'SXZU6' => ['q', 'b', 's', 'g'],
            'SXZU4' => ['q', 'b', 's', 'g'],

            'QZU3' => ['w', 'q', 'b',],
            'QZU3_S' => ['w', 'q', 'b',],
            'QZU6' => ['w', 'q', 'b',],
            'QZU6_S' => ['w', 'q', 'b',],

            'ZZU3' => ['q', 'b', 's',],
            'ZZU3_S' => ['q', 'b', 's',],
            'ZZU6' => ['q', 'b', 's',],
            'ZZU6_S' => ['q', 'b', 's',],

            'HZU3' => ['b', 's', 'g'],
            'HZU3_S' => ['b', 's', 'g'],
            'HZU6' => ['b', 's', 'g'],
            'HZU6_S' => ['b', 's', 'g'],
        ],
    ],

    // 玩法组名
    'group_name' => [
        'ssc' => [
            "WX" => "五星",
            "SX" => "四星",
            "Q3" => "前三",
            "Z3" => "中三",
            "H3" => "后三",
            "EX" => "二星",
            "DWD" => "定位胆",
            "BDW" => "不定位",
            "DXDS" => "大小单双",
            "QW" => "趣味",
            "RX" => "任选",
            "LH" => "龙虎",
        ],

        'lotto' => [
            "SM" => "三码",
            "EM" => "二码",
            "BDW" => "不定位",
            "DWD" => "定位胆",
            "QW" => "趣味",
            "RXFS" => "任选复式",
            "RXDS" => "任选单式",
            "RXDT" => "任选胆托",
        ],

        'k3' => [
            "DXDS" => "大小单双",
            "HZ" => "和值",
            'SBTH' => "三不同号",
            "STH" => "三同号",
            "SLH" => "三连号",
            "EBTH" => "二不同号",
            "ETH" => "二同号",
            "DTYS" => "单挑一骰",
        ],

        'sd' => [
            "SX" => "三星",
            "EX" => "二星",
            "DWD" => "定位胆",
            "BDW" => "不定位",
            "DXDS" => "大小单双",
        ],

        'ssl' => [
            "SX" => "三星",
            "EX" => "二星",
            "DWD" => "定位胆",
            "BDW" => "不定位",
            "DXDS" => "大小单双",
        ],

        'p3p5' => [
            "P3" => "排三",
            "EX" => "二星",
            "DWD" => "定位胆",
            "BDW" => "不定位",
            "DXDS" => "大小单双",
        ],

        'lhc' => [
            "TM" => "特码",
            "ZT" => "正特",
            "BB" => "半波",
            "SX" => "生肖",
            "WS" => "尾数",
            "ZF" => "总分",
            "BZ" => "不中",
        ],

        // pk10
        'pk10' => [
            "CDY" => "猜冠军",
            "CQ2" => "猜前二",
            "CQ3" => "猜前三",
            "CQ4" => "猜前四",
            "CQ5" => "猜前五",
            "DWD" => "定位胆",
        ],
    ],

    // 玩法行名
    'row_name' => [
        'default' => '默认',

        // 时时彩
        'zhixuan' => '直选',
        'zuxuan' => '组选',
        'quwei' => '趣味',
        'qita' => '其它',
        'dingweidan' => '定位胆',
        '3budingwei' => '三星不定位',
        '4budingwei' => '四星不定位',
        '5budingwei' => '五星不定位',
        'dxds' => '大小单双',
        'lhh' => '龙虎和',
        'budingwei' => '不定位',

        // 乐透
        'renxuanfushi' => "任选复式",
        'renxuandanshi' => "任选单式",
        'renxuandantuo' => "任选胆拖",
        'quwei' => "趣味",
        'dingweidan' => "定位胆",
        'budingwei' => "不定位",

        // 快三
        'hezhi' => '和值',
        'dantiaoyishai' => '单挑一筛',
        'erbutong' => '二不同',
        'ertonghao' => '二同号',
        'santonghao' => '三同号',
        'sanbutonghao' => '三不同号',
        'sanlianhao' => '三连号',
        'dxds' => '大小单双',

        //
        'diyiming' => '第一名',
        'dierming' => '第二名',
        'caiqianer' => '猜前二',
        'disanming' => '第三名',
        'caiqiansan' => '猜前三',
        'disiming' => '第四名',
        'caiqiansi' => '猜前四',
        'diwuming' => '第五名',
        'caiqianwu' => '猜前五',

        'diliuming' => '第六名',
        'caiqianliu' => '猜前六',

        'diqiming' => '第七名',
        'dibaming' => '第八名',
        'dijiuming' => '第九名',
        'dishiming' => '第十名',

        'caihezhi' => '猜和值',
        'longhudou' => '龙虎斗',

        'tm' => '特码',
        'zt' => '特码',
        'bb' => '特码',
        'sx' => '特码',
        'ws' => '特码',
        'zf' => '特码',
        'bz' => '特码',
    ],
];
