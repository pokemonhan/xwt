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
     * @param array $data Data.
     * @return string
     */
    public function saveItemAll(array $data)
    {
        self::truncate();

        DB::beginTransaction();
        foreach ($data as $item) {
            if (!self::find($item['id'])) {
                if (!self::insert($item)) {
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
