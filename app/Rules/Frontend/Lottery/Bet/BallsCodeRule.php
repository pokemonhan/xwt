<?php

namespace App\Rules\Frontend\Lottery\Bet;

use App\Models\Game\Lottery\LotteryList;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BallsCodeRule implements Rule
{
    protected $message = '注单号不符合';
    protected $lottery;
    protected $balls;

    /**
     * BallsCodeRule constructor.
     * @param  string  $lotterySign
     * @param  array  $balls
     * @throws Exception
     */
    public function __construct($lotterySign, array $balls)
    {
        $this->lottery = LotteryList::getLottery($lotterySign);
        $this->balls = $balls;
    }


    /**
     * Determine if the validation rule passes.
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
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
     * @param  string  $methodId
     * @param  string  $value
     * @return bool
     */
    private function pregMethodCode($methodId, $value): bool
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
     * @param  string  $methodId
     * @param  string  $code
     * @return bool
     */
    private function checkValidByLib($methodId, $code): bool
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
     * @param  string  $pattern
     * @param  string  $value
     * @return bool
     */
    private function checkValid($pattern, $value): ?bool
    {
        if (!preg_match($pattern, $value)) {
            $this->message = $this->lottery->series_id.'注单号不符合';
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param  string  $attribute
     * @return string
     */
    private function checkMethodId($attribute): string
    {
        $methodId = '';
        preg_match('/\d+/', $attribute, $matches);
        try {
            $methodId = $this->balls[$matches[0]]['method_id'];
        } catch (Exception $e) {
            if (!empty($this->balls)) {
                $arrMethod = json_decode($this->balls, true);
                $methodId = $arrMethod[$matches[0]]['method_id'];
            } else {
                Log::error($e->getMessage().$e->getTraceAsString().$attribute);
            }
        }
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
