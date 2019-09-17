<?php

namespace App\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PartnerDomain extends Model
{
    protected $table = 'partner_domain';

    public $rules = [
        'domain'        => 'required|min:2|max:64',
        'name'          => 'required|min:2|max:32',
        'type'          => 'required|in:1,2,3',
        'remark'        => 'required|min:2|max:128',
    ];

    static $typeList = [
        1 => "前台",
        2 => "后台",
        3 => "API",
    ];

    /**
     * 获取列表
     * @param $c
     * @return mixed
     */
    static function getList($c) {
        $query = self::orderBy('id', 'DESC');

        // 用户名
        if (isset($c['type']) && $c['type'] && $c['type'] != "all") {
            $query->where('type', $c['type']);
        }

        $currentPage    = isset($c['page_index']) ? intval($c['page_index']) : 1;
        $pageSize       = isset($c['page_size']) ? intval($c['page_size']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $items  = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $items, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 保存
    public function saveItem($data, $admin = null) {

        $validator  = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        // 域名　同一个类型 不能重复
        if (!$this->id) {
            $count = self::where('domain', '=', $data['domain'])->where('type', '=', $data['type'])->count();
            if ($count > 0) {
                return "对不起, 域名(domain)已经存在!!";
            }
        } else {
            $count = self::where('domain', '=', $data['domain'])->where('type', '=', $data['type'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 域名(domain)已经存在!!";
            }
        }

        $this->partner_sign     = $data['partner_sign'];
        $this->name             = $data['name'];
        $this->domain           = $data['domain'];
        $this->type             = $data['type'];
        $this->remark           = $data['remark'];
        $this->add_admin_id     = $admin ? $admin->id : '999999';
        $this->save();

        return true;
    }
}
