<?php
namespace App\Models\Casino;

use Illuminate\Support\Facades\DB;

/**
 * Class CasinoGamePlatform
 * @package App\Models\Casino
 */
class CasinoGamePlatform extends BaseCasinoModel
{
    /**
     * @var array
     */
    public $rules = [
        'main_game_plat_name'   => 'required|min:2|max:64',
    ];

    /**
     * @param array   $c        DATA.
     * @param integer $pageSize PageSize.
     * @return array
     */
    public function getList1(array $c, int $pageSize)
    {
        $query = self::orderBy('id', 'desc');

        $currentPage    = isset($c['page_index']) ? intval($c['page_index']) : 1;
        $pageSize       = isset($c['page_size']) ? intval($c['page_size']) : $pageSize;

        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $data   = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    /**
     * @param array $data Data.
     * @return string
     */
    public function saveItemAll(array $data)
    {
        DB::beginTransaction();
        foreach ($data as $v) {
            if (!self::find($v['id'])) {
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
     * @return array
     */
    public static function getOptions()
    {
        $options = [];
        $platforms = self::where('status', 1)->get();
        foreach ($platforms as $platform) {
            $options[] = [
                'name' => $platform->main_game_plat_name,
                'code' => $platform->main_game_plat_code,
            ];
        }
        return $options;
    }
}
