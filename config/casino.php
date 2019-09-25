<?php
return [
    'game_img_url'  => 'http://52.69.242.200:8086/',
    'secret_key'    => 'c518ae8a59bdb2fa89a943c7ab920669',
    'username'      => 'xuanwu',

    'categories'    => [
        'live'     => [
            'cateName'  => '真人视讯',
            'plats'     => [
                ['key' => 'ag', 'val' => 'AG'],
            ],
        ],
        'e-game'  => [
            'cateName'  => '电子游戏',
            'plats'     => [
                ['key' => 'mg', 'val' => 'MG'],
                ['key' => 'bg', 'val' => 'BG'],
                ['key' => 'bbin', 'val' => '波音'],
                ['key' => 'pt', 'val' => 'PT'],
            ],
        ],
        'card'    => [
            'cateName'  => '棋牌',
            'plats'     => [
                ['key' => 'ky', 'val' => '开元棋牌'],
                ['key' => 'lg', 'val' => '幸运棋牌'],
            ],
        ],
        'fishing'    => [
            'cateName'  => '捕鱼',
            'plats'     => [
                ['key' => 'bbin', 'val' => '波音'],
                ['key' => 'ag',   'val' => 'AG'],
                ['key' => 'bg',   'val' => '大游'],
            ],
        ],
    ],
];
