<?php
namespace App\Models\Game\Casino;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Validator;

class CasinoPlatform extends BaseModel {
    protected $table    = 'game_casino_platforms';
    public    $rules = [
        "main_game_plat_name"   => "required|min:2|max:64",
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
            $count = self::where('main_game_plat_code', '=', $data['main_game_plat_code'])->count();
            if ($count > 0) {
                return "对不起, 标识(code)已经存在!!";
            }
        } else {
            $count = self::where('main_game_plat_code', '=', $data['main_game_plat_code'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 标识(code)已经存在!!";
            }
        }

        $this->main_game_plat_name     = $data['main_game_plat_name'];
        $this->main_game_plat_code     = $data['main_game_plat_code'];
        $this->status                  = $data['status'] ? 1 : 0;
        $this->add_admin_id            = $admin ? $admin->id : '999999';
        $this->save();

        return true;
    }

    static function getOptions() {
        $options = [];
        $platforms = self::where("status", 1)->get();
        foreach ($platforms as $platform) {
            $options[] = [
                "name" => $platform->main_game_plat_name,
                "code" => $platform->main_game_plat_code,
            ];
        }
        return $options;
    }

}
