<?php
namespace App\Models\Casino;

use Illuminate\Support\Facades\DB;

/**
 * Class CasinoGameList
 * @package App\Models\Casino
 */
class CasinoGameList extends BaseCasinoModel
{
    /**
     * @param array $data Data.
     * @return string
     */
    public function saveItemAll(array $data)
    {
        DB::beginTransaction();
        foreach ($data as $v) {
            if (!self::find($v['id'])) {
                unset($v['deleted_at']);
                if (!self::insert($v)) {
                    DB::rollBack();
                    return 0;
                }
            }
        }
        DB::commit();
        return 1;
    }

    /**
     * @param integer $id ID.
     * @return integer
     */
    public function getItem(int $id)
    {
        $listData = self::where('id', $id)->first();
        if (!$listData) {
            $this->errMsg = '数据不存在';
            return 1;
        }
        return $listData;
    }

    /**
     * @return array
     */
    public function getHomeList()
    {
        // 获取所有的类型
        $categoryArr    = [];
        $categoryName   = [];
        $categories     = CasinoCategories::where('home', 1)->get();

        foreach ($categories as $v) {
            $categoryArr[]          = $v->code;
            $categoryName[$v->code] = $v->name;
        }

        $listModel = self::where('status', 1)->where('able_recommend', 0)->whereIN('category', $categoryArr)->skip(0)->take(100)->get();
        $data = [];

        foreach ($listModel as $v) {
            $data[$categoryName[$v->category]][] = $v->toArray();
        }
        return $data;
    }
}
