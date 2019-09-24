<?php

namespace App\Http\Middleware;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * 数据加密
 */
class Crypt
{
    //数据串间隔标志 前后统一
    private const LIMIT='aesrsastart';
    /**
     * Handle an incoming request.
     * @param Request  $request 传递的参数.
     * @param \Closure $next    Closure.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $resources = $this->verification($request, $next);
        if ($resources['success'] === false) {
            return $this->returnMsgout(false, [], $resources['code']);
        } elseif ($resources['success'] === true && !empty($resources['data'])) {
            return $resources['data'];
        } else {
            $inData = $resources['inData'];
        }

        $requestCryptData = explode(self::LIMIT, $inData);
        if (count($requestCryptData) !== 3) {
            return $this->returnMsgout(false, [], '100501');
        }

        $data = $requestCryptData[0]; //固定位 数组 自生成
        $iValue = self::rsaDeCrypt($requestCryptData[1]);
        if ((bool) $iValue === false) {
            return $this->returnMsgout(false, [], '100502');
        }

        $iKey = self::rsaDeCrypt($requestCryptData[2]);
        if ((bool) $iKey === false) {
            return $this->returnMsgout(false, [], '100503');
        }

        $deAesData = self::deAesCrypt($data, $iKey, $iValue);
        if ((bool) $deAesData === false) {
            return $this->returnMsgout(false, [], '100505');
        }

        $deData = json_decode((string) $deAesData, true);
        if (is_null($deData)) {
            return $this->returnMsgout(false, [], '100504');
        }

        foreach ($deData as $dataKey => $dataValue) {
            $request[$dataKey] = $dataValue;
        }
        unset($request['data']);
        return $next($request);
    }

    /**
     * 返回信息
     * @param  boolean $success 是否成功.
     * @param  array   $data    返回数据.
     * @param  string  $code    信息编码.
     * @return JsonResponse
     */
    public function returnMsgout(bool $success, array $data, string $code = '') :JsonResponse
    {
        $contll = new FrontendApiMainController();
        return $contll->msgOut($success, $data, $code);
    }
    /**
     * RSA解密 自带私钥
     * @param  mixed $rsaData 数据.
     * @return string|false
     */
    private function rsaDeCrypt($rsaData)
    {
        //中间件还未生成缓存 所以将私钥配置在此 以减少系统开销
        $pkcs8_private = '-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAK3m6BabZZ2qQwjm
IOBOZ1q9g9OnqGapuinLs3182ew2LAQT62iLReBCNB64TRh/tU4iIIjx5bNRpNZ8
IrcP92YVNuxMrdSCqXpC5gpGFKf1CfG0SrO+TPmO/d1zexJq/yArc7HbYMFZRfks
7BjnaQGJ5rCVEVyS/y+0I5hU+t37AgMBAAECgYBHuq6QZJiNZ+Uujn2RB8Xpz7HI
Y6c6cVewVa07XXwlylJtfCnlfUzdK3GEGlDygfqut7Tjx6MPS+XJ8nn/OD661nRX
bHnEzgPEFPJmJ926NkZpz13Ox9n8I7o1LWwduWsxOebkSKi6w8fWXLCaEZx7stV8
xHvqZUAVlOkdJwcsAQJBANj6wviGzy/nvyOeFREtLlvC+KHjBVtqM+w3uiKzASyM
JjKYM7wjLAviX0+S9/F3h3d2HoGGxh8HZbzYi0FpZ0ECQQDNLPQPbMkYL/HkM3kC
3U2ebFKkx+qjp7cv28wd7tPmZ6mxHA8ihtANCt0F0J4F3fSpWLWXDs2Yw8oR+UjG
jZI7AkBui0s77QqvgGU8EzTufFNLAslDSPMYwMHVTgrx1Lr7ZCetzSdGabDuGRWv
59OUXO5SaYZfPTfA5TbrAHPqDnZBAkBJRUBzMboutRCBGhChAT7y0GRDDFGy1/YH
VUrzdZKeuW5UHV0aS2KJBdQge3uzRKxWvaM7qsGpSGIlQQzIO055AkBvkOcvyrkV
s+RmDzYuKUoG0zIjmIZidcaTP1p2ngqCl/RXl1evVAmXet26uDPkFtmOGvFTngZM
Web+LMihoBTa
-----END PRIVATE KEY-----';
        $baseRsa = base64_decode($rsaData);
        $flag = openssl_private_decrypt((string) $baseRsa, $deRsaCryptData, $pkcs8_private);
        if ((bool) $flag === false) {
            return false;
        }
        return $deRsaCryptData;
    }
    /**
     * AES解密 加密方式 AES-128-CBC
     * @param  mixed $enAes   数据.
     * @param  mixed $dataKey 键.
     * @param  mixed $iValue  值.
     * @return string|false
     */
    private function deAesCrypt($enAes, $dataKey, $iValue)
    {
        $baseEnAes = base64_decode($enAes);
        return openssl_decrypt((string) $baseEnAes, 'AES-128-CBC', $dataKey, OPENSSL_RAW_DATA, $iValue);
    }

    /**
     * 验证
     * @param Request  $request 传递的参数.
     * @param \Closure $next    Closure.
     * @return array
     */
    private function verification(Request $request, Closure $next)
    {
        //系统配置为不加密传输数据时直接放行
        $isCryptData = configure('is_crypt_data');
        if ((bool) $isCryptData === false) {
            //配置为不加密数据时传递的数据还是加密的，则返回100507让前端刷新该加密配置
            if (isset($request['data'])) {
                return ['success' => false, 'data' => [], 'code' => '100507'];
            } else {
                return ['success' => true, 'data' => $next($request)];
            }
        }
        //空参放行
        $requestNum = count($request->request);
        if (!$requestNum) {
            return ['success' => true, 'data' => $next($request)];
        }
        //本地模式关闭参数唯一性判断
        if (config('app.env') !== 'local') {
            //检验参数是否符合规范 系统只允许接入一个名为DATA的参数
            if ($requestNum !== 1 || !isset($request['data'])) {
                return ['success' => false, 'data' => [], 'code' => '100507'];
            }
        }
        $inData = $request->input('data');
        //带DATA数据却为null
        if (is_null($inData)) {
                return ['success' => false, 'data' => [], 'code' => '100506'];
        }
        //错误返回
        if (!is_string($inData)) {
                return ['success' => false, 'data' => [], 'code' => '100500'];
        }
        return ['success' => true, 'inData' => $inData];
    }
}
