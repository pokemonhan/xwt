<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;
use RicardoFontanelli\LaravelTelegram\Telegram;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }
        //###### sending errors to tg //Harris ############
        $appEnvironment = app()->environment();
        if ($appEnvironment == 'develop' || $appEnvironment == 'test-develop') {
            $agent = new Agent();
            $os = $agent->platform();
            $osVersion = $agent->version($os);
            $browser = $agent->browser();
            $bsVersion = $agent->version($browser);
            $robot = $agent->robot();
            if ($agent->isRobot()) {
                $type = 'robot';
            } elseif ($agent->isDesktop()) {
                $type = 'desktop';
            } elseif ($agent->isTablet()) {
                $type = 'tablet';
            } elseif ($agent->isMobile()) {
                $type = 'mobile';
            } elseif ($agent->isPhone()) {
                $type = 'phone';
            } else {
                $type = 'other';
            }
            $error = [
                'origin' => request()->headers->get('origin'),
                'ips' => json_encode(request()->ips()),
                'user_agent' => request()->server('HTTP_USER_AGENT'),
                'lang' => json_encode($agent->languages()),
                'device' => $agent->device(),
                'os' => $os,
                'browser' => $browser,
                'bs_version' => $bsVersion,
                'os_version' => $osVersion,
                'device_type' => $type,
                'robot' => $robot,
                'inputs' => Request::all(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'previous' => $exception->getPrevious(),
                'TraceAsString' => $exception->getTraceAsString(),
            ];
            $telegram = new Telegram(config('telegram.token'), config('telegram.botusername'));
            $telegram->sendMessage(config('telegram.chats.'.$appEnvironment), e((string) json_encode($error, JSON_PRETTY_PRINT)));
        }
        Log::channel('daily')->error(
            $exception->getMessage(),
            array_merge($this->context(), ['exception' => $exception])
        );
//        parent::report($exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            $msg = $exception->getMessage();
            if ($msg == 'Unauthenticated.') {
                $result = [
                    'success' => false,
                    'code' => $exception->getCode(),
                    'data' => [],
                    'message' => '您没有权限操作 请尝试先登录',
                ];
            } else {
                $result = ['message' => $msg];
            }
            return response()->json($result);
        } else {
            return redirect()->guest($exception->redirectTo() ?? route('login'));
        }
    }
}
