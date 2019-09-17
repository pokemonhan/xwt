<?php
namespace App\Models\Game\Casino;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Validator;

class CasinoCategories extends BaseModel {
    protected $table    = 'game_casino_categories';
    public    $rules = [
        "name"   => "required|min:2|max:64",
        "code"   => "required|min:2|max:64",
    ];

    /**
     * @param $c
     * @param $pageSize
     * @return mixed
     */
    static function getList($c, $pageSize = 20) {
        $query = self::orderBy('id', 'desc');

        $currentPage    = isset($c['page_index']) ? intval($c['page_index']) : 1;
        $pageSize       = isset($c['page_size']) ? intval($c['page_size']) : $pageSize;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $data   = $query->skip($offset)->take($pageSize)->get();


        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 保存
    public function saveItem($data, $admin = null) {
        $validator  = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        // Sign 不能重复
        if (!$this->id) {
            $count = self::where('code', '=', $data['code'])->count();
            if ($count > 0) {
                return "对不起, 标识(code)已经存在!!";
            }
        } else {
            $count = self::where('code', '=', $data['code'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 标识(code)已经存在!!";
            }
        }

        $this->name                 = $data['name'];
        $this->code                 = $data['code'];
        $this->home                 = isset($data['home']) ? 1 : 0;
        $this->status               = isset($data['status']) ? 1 : 0;
        $this->add_admin_id         = $admin ? $admin->id : '999999';
        $this->save();

        return true;
    }

    // 获取选项
    static function getOptions() {
        $options = [];
        $list = self::where("status", 1)->get();
        foreach ($list as $item) {
            $options[] = [
                "name" => $item->name,
                "code" => $item->code,
            ];
        }
        return $options;
    }
}
