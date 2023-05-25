<?php

namespace App\Http\Middleware;

use App\Facades\Core;
use App\Helpers\CoreHelper;
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
        try {
            $data=CoreHelper::decodeJwt($token);
            $isExpire=CoreHelper::expireToken($data['expire']);
            if($isExpire){
                throw new Exception(__('auth.expire'));
            }
            return $next($request);
        }catch (Exception $exception){
            throw new $exception;
        }
    }
}
