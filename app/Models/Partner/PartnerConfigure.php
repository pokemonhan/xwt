<?php

namespace App\Models\Admin;

/**
 * 伙伴配置
 * Class Configure
 * @package App\Models\Admin
 */
class PartnerConfigure extends Base
{
    protected $table = 'partner_configures';

    /**
     * @param $c
     * @param $pageSize
     * @return mixed
     */
    static function getConfigList($c, $pageSize = 20) {
        $query = self::orderBy('id', 'desc');

        // 上级
        if (isset($c['pid']) && $c['pid']) {
            $query->where('pid', '=', $c['pid']);
        } else {
            $query->where('pid', '=', 0);
        }

        $currentPage    = isset($c['pageIndex']) ? intval($c['pageIndex']) : 1;
        $pageSize       = isset($c['pageSize']) ? intval($c['pageSize']) : $pageSize;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $menus  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $menus, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    public function saveItem($parent, $params, $adminId = 0) {

        $name   = $params["name"];
        if (!$name) {
            return "对不起, 无效的用户名";
        }

        // sign
        $sign   = trim($params["sign"]);
        if (!$sign) {
            return "对不起, SIGN不存在!!";
        }

        if ($parent && !$this->id) {
            $sign = $parent->sign . "_" . $sign;
        }

        $_config = Configure::where('sign', '=', $sign)->first();
        if (!$this->id && $_config) {
            return "对不起, SIGN已经存在!!";
        }

        $value          = trim($params["value"]);
        $description    = trim($params["description"]);

        // 如果是添加 查找最后一个id
        if (!$this->id) {
            $pid = $parent ? $parent->id : 0;
            $lastItem = self::where("pid", $pid)->orderBy("id", 'DESC')->first();
            $this->id = $lastItem ? $lastItem->id + 1 : $parent->id * 1000 + 1;
        }

        $this->pid              = $parent ? $parent->id : 0;
        $this->sign             = $sign;
        $this->name             = $name;
        $this->value            = $value;
        $this->description      = $description;
        // 变更人
        if (!$this->id) {
            $this->add_admin_id             = $adminId;
        } else {
            $this->last_update_admin_id     = $adminId;
        }

        $this->save();

        return true;
    }

    static function getAllConfig(){
        $config = self::select('sign', 'value')->where('status', 1)->get();
        return $config;
    }

    // 获取缓存
    static function getConfigCache($key, $default) {
        $data = self::getCacheData('sys_configure');

        if (!$data) {
            self::flushConfigCache();
            $data = self::getCacheData('sys_configure');
        }

        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    // 设置
    static function configureSet($key, $value) {
        db()->table("sys_configures")->where('sign', $key)->update(['value' => $value]);
        return true;
    }

    // 获取缓存
    static function flushConfigCache() {
        $res = self::where('status', '=', 1)->get();

        $data = [];
        foreach ($res as $item) {
            $data[$item->sign] = $item->value;
        }

        self::saveCacheData('sys_configure', $data);
    }
}
