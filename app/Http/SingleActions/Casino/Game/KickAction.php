<?php
namespace App\Http\SingleActions\Casino\Game;

use App\Http\Controllers\CasinoApi\CasinoApiMainController;
use App\Models\Casino\CasinoGameApiLog;

/**
 * Class KickAction
 * @package App\Http\SingleActions\Casino\Game
 */
class KickAction
{
    /**
     * @var CasinoGameApiLog
     */
    protected $model;

    /**
     * @param  CasinoGameApiLog $CasinoGameApiLog Log.
     */
    public function __construct(CasinoGameApiLog $CasinoGameApiLog)
    {
        $this->model = $CasinoGameApiLog;
    }

    /**
     * 踢线
     * @param  CasinoApiMainController $contll     基类.
     * @param  array                   $inputDatas 娱乐城.
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
                'accountUserName'   => $contll->partnerUser->username,
            ];
            $returnVal['param'] = json_encode($paramArr);       // 日志

            $paramStr       = http_build_query($paramArr);
            $paramEncode    = $contll->authcode($paramStr, 'ENCODE', $contll->secretkey);

            $apiUrl = $contll->apiUrl . '/kick?' . $paramStr . '&param=' . $paramEncode;

            $returnVal['call_url'] = $apiUrl;                   // 日志

            $data   = $contll->request('GET', $apiUrl);

            $returnVal['return_content'] = json_encode($data);  // 日志
            $this->model->saveItem($returnVal);

            if ($data['success']) {
                return $contll->msgOut(true, $data);
            }
            return $contll->msgOut(false, $data);
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
