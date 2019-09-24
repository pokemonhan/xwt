<?php
namespace App\Http\SingleActions\Casino\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Models\Casino\CasinoGameApiLog;

/**
 * Class JoinGameAction
 * @package App\Http\SingleActions\Casino\Game
 */
class JoinGameAction
{
    /**
     * @var CasinoGameApiLog
     */
    protected $model;

    /**
     * JoinGameAction constructor.
     */
    public function __construct()
    {
        $this->model = new CasinoGameApiLog();
    }

    /**
     * 进入游戏
     * @param  CasinoApiMainController $contll     基类.
     * @param  array                   $inputDatas 参数.
     * @return string
     */
    public function execute(CasinoApiMainController $contll, array $inputDatas)
    {
        $returnVal  = [
            'api'       => 'GetBalance',
            'username'  => $contll->partnerUser->username,
            'user_id'   => $contll->partnerUser->id,
            'ip'        => real_ip(),
        ];

        try {
            $paramArr = [
                'username'          => $contll->username,
                'mainGamePlat'      => $inputDatas['mainGamePlat'],
                'accountUserName'   => $contll->partnerUser->username ?? 'ling1',
                'gamecode'          => $inputDatas['gamecode'],
                'demo'              => $inputDatas['demo'] ?? 1,
                'ip'                => real_ip(),
                'isMobile'          => $inputDatas['isMobile'] ?? 1,
            ];

            $returnVal['params'] = json_encode($paramArr);       // 日志

            $paramStr       = http_build_query($paramArr);
            $paramEncode    = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey, 0);

            $apiUrl = $contll->apiUrl . '/joinGame?' . $paramStr . '&param=' . urlencode($paramEncode);

            $returnVal['call_url'] = $apiUrl;                   // 日志

            $returnVal['return_content'] = '直接跳转入游戏';  // 日志
            $this->model->saveItem($returnVal);

            return $contll->msgOut(true, $apiUrl);
        } catch (\Exception $e) {
            $returnVal['return_content'] = json_encode([$e->getMessage(),$e->getLine(),$e->getFile()]);  // 日志
            $this->model->saveItem($returnVal);

            return $contll->msgOut(
                false,
                [],
                '',
                '对不起, '.$e->getMessage().'|'.$e->getFile().'|'.$e->getLine()
            );
        }
    }
}
