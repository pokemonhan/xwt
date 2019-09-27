<?php
namespace App\Http\SingleActions\Backend\Casino\Game\Lists;

use App\Http\Controllers\BackendApi\Casino\CasinoApiMainController;
use App\Models\Casino\CasinoGameList;

/**
 * Class GetGameListsAction
 * @package App\Http\SingleActions\Backend\Casino\Game\Lists
 */
class GetGameListsAction
{
    /**
     * @var CasinoGameList
     */
    protected $model;

    /**
     * @param  CasinoGameList $CasinoGameList 娱乐城游戏.
     */
    public function __construct(CasinoGameList $CasinoGameList)
    {
        $this->model = $CasinoGameList;
    }
    /**
     * @param  CasinoApiMainController $contll C.
     * @return boolean
     */
    public function execute(CasinoApiMainController $contll)
    {
        $plats      = CasinoGamePlatform::get();
        foreach ($plats as $plat) {
            $data       = $this->callCasinoList($contll, $plat->main_game_plat_code);
            $casinoList = json_decode($data, 1);
            if (empty($casinoList)) {
                return $contll->msgOut(true, $data);
            }

            unset($casinoList['data1']);
            if (!$this->model->saveItemAll($casinoList)) {
                return $contll->msgOut(false, []);
            }
        }

        return $contll->msgOut(true, []);
    }

    /**
     * @param CasinoApiMainController $contll 娱乐城基类.
     * @param string                  $plat   平台.
     * @return string
     */
    public function callCasinoList(CasinoApiMainController $contll, string $plat)
    {
        $paramArr = [
            'username'          => $contll->username,
            'mainGamePlat'      => $plat,
        ];

        $returnVal['params'] = json_encode($paramArr);       // 日志
        $paramStr            = http_build_query($paramArr);
        $paramEncode         = casino_authcode($paramStr, 'ENCODE', $contll->secretkey, 0);

        $apiUrl = $contll->apiUrl . '/getGameList?' . $paramStr . '&param=' . urlencode($paramEncode);
        $data   = casino_request('GET', $apiUrl, [], '', 0, 0, 0);

        return $data;
    }
}
