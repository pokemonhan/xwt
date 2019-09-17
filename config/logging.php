<?php

use App\Services\GraylogSetup;
use App\Services\Logs\BackendLogs\BackendLogMonolog;
use App\Services\Logs\FrontendLogs\FrontendLogMonolog;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'dy-activity' => [
            'driver' => 'daily',
            'path' => storage_path('logs/dy-activity.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'issues' => [
            'driver' => 'daily',
            'path' => storage_path('logs/issues.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'trace' => [
            'driver' => 'daily',
            'path' => storage_path('logs/trace.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'operate' => [
            'driver' => 'daily',
            'path' => storage_path('logs/operate.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],
        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
        'graylog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\GelfHandler::class,
            'handler_with' => [
//                'publisher' =>  GraylogSetup::getGelfPublisher(),
            ],
            'formatter' => GelfMessageFormatter::class
        ],
        /*'byqueue' => [
            'driver' => 'custom',
            'via' => LogMonolog::class,

        ],*/
        'frontend-by-queue' => [
            'driver' => 'custom',
            'via' => FrontendLogMonolog::class,

        ],
        'apibyqueue' => [
            'driver' => 'custom',
            'via' => BackendLogMonolog::class,
        ],
        'account' => [// Clog::account
            'driver' => 'daily',
            'path' => storage_path('logs/account/accounts.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'platform' => [
            'driver' => 'daily',
            'path' => storage_path('logs/platform/platform.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'game' => [ // Clog::gameError
            'driver' => 'daily',
            'path' => storage_path('logs/game/game.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'bet' => [ // Clog::userBet
            'driver' => 'daily',
            'path' => storage_path('logs/bet/bet.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'recharge' => [ // Clog::recharge    Clog::rechargeLog
            'driver' => 'daily',
            'path' => storage_path('logs/recharge/recharge.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'withdraw' => [ // Clog::withdraw    Clog::withdrawLog
            'driver' => 'daily',
            'path' => storage_path('logs/withdraw/withdraw.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'log' => [ // Clog::lockError
            'driver' => 'daily',
            'path' => storage_path('logs/account/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'addchild' => [ // Clog::userAddChild
            'driver' => 'daily',
            'path' => storage_path('logs/user/addchild.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'open-center' => [//开奖中心日志
            'driver' => 'daily',
            'path' => storage_path('logs/opencenter.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'daysalary' => [
            'driver' => 'daily',
            'path' => storage_path('logs/daysalary.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'commissions' => [
            'driver' => 'daily',
            'path' => storage_path('logs/commissions.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'pay-recharge' => [
            'driver' => 'daily',
            'path' => storage_path('logs/pay-recharge.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'pay-withdraw' => [
            'driver' => 'daily',
            'path' => storage_path('logs/pay-withdraw.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'calculate-prize' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calculate_prize.log'),
            'level' => 'debug',
            'days' => 14,
        ],
    ],

];
