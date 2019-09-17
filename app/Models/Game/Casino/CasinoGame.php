<?php
namespace App\Models\Game\Casino;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Validator;

class CasinoGame extends BaseModel {
    protected $table    = 'game_casino_game_lists';

    public    $rules = [
        "main_game_plat_code"           => "required",
        "cn_name"                       => "required|min:2｜max:64",
        "en_name"                       => "required|min:2｜max:64",
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
        $data  = $query->skip($offset)->take($pageSize)->get();


        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 保存
    public function saveItem($data, $admin) {
        $validator  = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        // en_name 同平台下不能重复
        if (!$this->id) {
            $count = self::where('en_name', '=', $data['en_name'])->where('main_game_plat_code', $data['main_game_plat_code'])->count();
            if ($count > 0) {
                return "对不起, 同平台下, 标识(en_name)已经存在!!";
            }
        } else {
            $count = self::where('en_name', '=', $data['en_name'])->where('main_game_plat_code', $data['main_game_plat_code'])->where("id", "<>", $this->id)->count();
            if ($count > 0) {
                return "对不起, 同平台下, 标识(en_name)已经存在!!";
            }
        }

        $this->main_game_plat_code          = $data['main_game_plat_code'];
        $this->en_name                      = $data['en_name'];
        $this->cn_name                      = $data['cn_name'];

        $this->pc_game_code                 = isset($data['pc_game_code']) ? $data['pc_game_code'] : '';
        $this->pc_game_deputy_code          = isset($data['pc_game_deputy_code']) ? $data['pc_game_deputy_code'] : '';
        $this->mobile_game_code             = isset($data['mobile_game_code']) ? $data['mobile_game_code'] : '';
        $this->mobile_game_deputy_code      = isset($data['mobile_game_deputy_code']) ? $data['mobile_game_deputy_code'] : '';

        $this->record_match_code            = isset($data['record_match_code']) ? $data['record_match_code'] : '';
        $this->record_match_deputy_code     = isset($data['record_match_deputy_code']) ? $data['record_match_deputy_code'] : '';

        $this->img                          = isset($data['img']) ? $data['img'] : '';
        $this->type                         = isset($data['type']) ? $data['type'] : '';
        $this->category                     = isset($data['category']) ? $data['category'] : '';
        $this->line_num                     = isset($data['line_num']) ? $data['line_num'] : '';

        $this->able_demo                    = isset($data['able_demo']) ? $data['able_demo'] : '';
        $this->able_recommend               = isset($data['able_recommend']) ? $data['able_recommend'] : '';
        $this->bonus_pool                   = isset($data['bonus_pool']) ? $data['bonus_pool'] : '';
        $this->line_num                     = isset($data['line_num']) ? $data['line_num'] : '';


        $this->add_admin_id             = $admin ? $admin->id : '999999';
        $this->save();

        return true;
    }


    // 获取单个详细信息
    public function getItem($id){
        $listData = self::where('id',$id)->first();
        if (!$listData){
            $this->errMsg = '数据不存在';
            return 1;
        }
        return $listData;
    }

    // 获取ｈｏｍｅ信息
    public function getHomeList(){

        // 获取所有的类型
        $categoryArr    = [];
        $categoryName   = [];
        $categories     = CasinoCategories::where('home',1)->get();

        foreach ($categories as $k => $v){
            $categoryArr[]          = $v->code;
            $categoryName[$v->code] = $v->name;
        }

        $listModel = self::where('status',1)->where('able_recommend',0)->whereIN('category',$categoryArr)->skip(0)->take(100)->get();
        $data = [];
        foreach ($listModel as $k => $v){
            $data[$categoryName[$v->category]][] = $v->toArray();
        }
        return $data;
    }
}
