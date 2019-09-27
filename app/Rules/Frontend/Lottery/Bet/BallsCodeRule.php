<?php

namespace App\Rules\Frontend\Lottery\Bet;

use App\Models\Game\Lottery\LotteryList;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

/**
 * BallsCodeRule
 */
class BallsCodeRule implements Rule
{
    /**
     * @var string
     */
    protected $message = '注单号不符合';

    /**
     * @var LotteryList
     */
    protected $lottery;

    /**
     * @var array
     */
    protected $balls;

    /**
     * BallsCodeRule constructor.
     * @param string $lotterySign 彩种.
     * @param array  $balls       下注参数.
     */
    public function __construct(string $lotterySign, array $balls)
    {
        $this->lottery = LotteryList::getLottery($lotterySign);
        $this->balls = $balls;
    }


    /**
     * Determine if the validation rule passes.
     * @param mixed $attribute 属性.
     * @param mixed $value     下注号码string.
     * @return boolean
     */
    public function passes($attribute, $value)
    {
        $methodId = $this->checkMethodId($attribute);
        $result = $this->pregMethodCode($methodId, $value);
        if ($result === true) {
            $result = $this->checkValidByLib($methodId, $value);//用lib 里面的文件 去校验
        }
        return $result;
    }

    /**
     * @param string $methodId 玩法.
     * @param string $value    值.
     * @return boolean
     */
    private function pregMethodCode(string $methodId, string $value): bool
    {
        $pattern = Config::get('game.method_regex.'.$this->lottery->series_id.'.'.$methodId);
        if (empty($pattern)) {
            $pattern = Config::get('game.method_regex.'.$this->lottery->series_id.'.default');
        }
        if ($pattern !== null) {
            return $this->checkValid($pattern, $value);
        } else {
            return true;
        }
    }

    /**
     * @param string $methodId 玩法.
     * @param string $code     号码.
     * @return boolean
     */
    private function checkValidByLib(string $methodId, string $code): bool
    {
        $result = true;
        $method = $this->lottery->getMethod($methodId);
        $rule = [
            'status' => 'required|in:1', //玩法状态
            'object' => 'required', //玩法对象
            'method_name' => 'required', // 玩法未定义
        ];
        $messages = [
            'status.required' => '玩法未开放',
        ];
        $validator = Validator::make($method, $rule, $messages);
        $oMethod = $method['object']; // 玩法 - 对象
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();
            $result = false;
        } elseif (!$oMethod->regexp($code)) {// 投注号码
            $this->message = '对不起, 玩法'.$methodId.', 注单号码不合法!';
            $result = false;
        }
        return $result;
    }

    /**
     * @param string $pattern 玩法.
     * @param string $value   号码.
     * @return boolean
     */
    private function checkValid(string $pattern, string $value): ?bool
    {
        if (!preg_match($pattern, $value)) {
            $this->message = $this->lottery->series_id.'注单号不符合';
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $attribute 属性.
     * @return string
     */
    private function checkMethodId(string $attribute): string
    {
        preg_match('/\d+/', $attribute, $matches);
        $methodId = $this->balls[$matches[0]]['method_id'];
        return $methodId;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
