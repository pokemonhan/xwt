<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\BaseCache;

class DeleteCachePicControl extends Command
{
    use BaseCache;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteCachePic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时删除没上传活动的图片';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $redisKey = 'cleaned_images';
        $checkCache = self::hasTagsCache($redisKey);
        if ($checkCache !== false) {
            $cleanedImages = self::getTagsCacheData($redisKey);
            foreach ($cleanedImages as $key => $pic) {
                if (!isset($pic['expire_time']) || $pic['expire_time'] < time()) {
                    $path = 'public/' . $pic['path'];
                    if (file_exists($path)) {
                        if (!is_writable(dirname($path))) {
                        } else {
                            unlink($path);
                            unset($cleanedImages[$key]);
                        }
                    } else {
                        unset($cleanedImages[$key]);
                    }
                }
            }
            self::saveTagsCacheData($redisKey,$cleanedImages);
        }
    }
}
