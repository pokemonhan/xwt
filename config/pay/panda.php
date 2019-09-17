<?php
return [
    'merchant_id'   => '11810007',
    'key'           => 'o55kiqwp6f5hk4s8f79yjhmsf4r3glhd',
    'gateway'       => 'https://api.cqvip9.com/v1_beta/',
    'notify_url'    => env('APP_URL').'/web-api/pay/recharge_callback',
    'callback_url'  => env('APP_URL').'/web-api/pay/recharge_callback',
];
