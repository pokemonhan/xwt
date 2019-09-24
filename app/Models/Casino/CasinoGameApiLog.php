<?php
namespace App\Models\Casino;

use Illuminate\Support\Facades\Validator;

/**
 * Class CasinoGameApiLog
 * @package App\Models\Casino
 */
class CasinoGameApiLog extends BaseCasinoModel
{
    /**
     * @var array
     */
    public $rules = [];

    /**
     * @param array   $condition 参数.
     * @param integer $pageSize  每页多少条.
     * @return mixed
     */
    public static function getGameList(array $condition, int $pageSize = 20)
    {
        $query = self::orderBy('id', 'desc');

        $currentPage    = isset($condition['pageIndex']) ? intval($condition['pageIndex']) : 1;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $data   = $query->skip($offset)->take($pageSize)->get();


        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    /**
     * @param array   $data   更新数据.
     * @param integer $listId 更新id.
     * @return boolean
     */
    public function saveItem(array $data, int $listId = 0)
    {
        $validator  = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $this->errMsg = $validator->errors()->first();
            return 0;
        }

        return $this->saveBase($data, $listId);
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
}
