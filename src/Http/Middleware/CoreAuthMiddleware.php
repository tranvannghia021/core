<?php

namespace Devtvn\Social\Http\Middleware;

use Devtvn\Social\Helpers\CoreHelper;
use Closure;
use Illuminate\Http\Request;
use Mockery\Exception;

class CoreAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token=$request->header('Authorization');
        if(is_null($token)) throw new Exception(__('core.required_token'));
        try {
            $data=CoreHelper::decodeJwt($token);
            $isExpire=CoreHelper::expireToken($data['expire']);

            if($isExpire){
                throw new \Exception(__('core.expire'));
            }
            return $next($request);
        }catch (Exception $exception){
            throw new \Exception(__('core.jwt'));
        }
    }
}
