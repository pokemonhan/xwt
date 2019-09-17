<?php

$phpbin = env("PHP_BIN_PATH", '/usr/bin/php-7.1');
$basepath   = __DIR__.'/../'; //根目录

$cron_path          = __DIR__.'/../storage/logs/queue/cron_';

return array(
    'phpbin'        => $phpbin,
    'basepath'      => $basepath,
    'cron_path'     => $cron_path,

    'crontab' => array(
        // ------------队列守护------------
        'queue_default'=>array(
            'name' => '一般队列守护者',
            'cron' => '* * * * *',
            'command' => "{$phpbin} {$basepath}artisan queue:loop 1 --queue=default --sleep=1 --tries=3",
            'logfile' => $cron_path.'queue_default.log',
        ),

        'queue_logs'=>array(
            'name'      => '日志队列',
            'cron'      => '* * * * *',
            'command'   => "{$phpbin} {$basepath}artisan queue:loop 1 --queue=apilogs,logs --sleep=1 --tries=3",
            'logfile'   => $cron_path.'queue_logs.log',
        ),

        'queue_issue'=>array(
            'name'      => '奖期队列',
            'cron'      => '* * * * *',
            'command'   => "{$phpbin} {$basepath}artisan queue:loop 1 --queue=issue --sleep=1 --tries=3",
            'logfile'   => $cron_path.'queue_issue.log',
        ),

        'queue_open'=>array(
            'name'      => '开奖队列',
            'cron'      => '* * * * *',
            'command'   => "{$phpbin} {$basepath}artisan queue:loop 1 --queue=open_numbers --sleep=1 --tries=3",
            'logfile'   => $cron_path.'queue_open.log',
        ),

        /** ---- 一般cron ----- */
        'cron_stat_gen'=>array(
            'name'      => '用户数据初始化',
            'cron'      => '* * * * *',
            'command'   => "{$phpbin} {$basepath}artisan schedule:run",
            'logfile'   => $cron_path.'schedule.log',
        ),
    ),
);
