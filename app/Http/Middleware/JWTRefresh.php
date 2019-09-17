<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTRefresh extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->checkForToken($request);
        $user = $this->auth->user();
        $oldToken = $user->remember_token;
        try {
            $token = $this->auth->parseToken()->refresh();
            $user->remember_token = $token;
            $user->save();
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
        $response = $next($request);
        // Send the refreshed token back to the client.
        return $this->setAuthenticationHeader($response, $token);
    }
}
