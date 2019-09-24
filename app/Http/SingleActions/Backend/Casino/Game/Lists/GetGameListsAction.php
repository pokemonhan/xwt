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
        $paramArr = [
            'username'          => $contll->username,
            'mainGamePlat'      => 'pt',
        ];

        $returnVal['params'] = json_encode($paramArr);       // 日志

        $paramStr           = http_build_query($paramArr);
        $paramEncode        = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey);

        $apiUrl = $contll->apiUrl . '/getGameList?' . $paramStr . '&param=' . urlencode($paramEncode);

        $data   = $contll->request('GET', $apiUrl, [], '', 0, 0, 0);
        $data1  = json_decode($data, 1);
        if (empty($data1)) {
            return $contll->msgOut(true, $data);
        }
        unset($data1['data1']);
        if ($this->model->saveItemAll($data1)) {
            return $contll->msgOut(true, []);
        }
        return $contll->msgOut(false, []);
    }
}
