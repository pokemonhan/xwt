<?php namespace App\Lib;

use App\Models\Admin\SystemConfiguration;
use Illuminate\Support\Facades\Cache;

class Configure
{
    //system_configurations
    public function get($key, $default = null)
    {
        $tags = self::getTags();
        return Cache::tags($tags)->get($key, static function () use ($key, $default, $tags) {
            $res = SystemConfiguration::where('sign', '=', $key)->where('status', '=', 1)->first();
            if (!is_null($res)) {
                Cache::tags($tags)->forever($key, $res->value);
                return $res->value;
            } else {
                return $default;
            }
        });
    }

    public static function set($key, $value)
    {
        $tags = self::getTags();
        SystemConfiguration::where('sign', '=', $key)->update(['value' => $value]);
        Cache::tags($tags)->forget($key);
    }

    public function flush()
    {
        $tags = self::getTags();
        Cache::tags($tags)->flush();
    }

    public static function getTags()
    {
        return 'configure';
    }
}
