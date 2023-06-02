<?php

namespace Devtvn\Social\Http\Middleware;

use Devtvn\Social\Helpers\CoreHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class VerifyMiddleware
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
        try {
            $token=$request->input('z');
            $result=CoreHelper::decodeState($token);
            $isExpire=CoreHelper::expireToken($result['expire']);
            if(Cache::get('ip_'.$request->input('type').$result['id']) === null){
                Cache::put('ip_'.$request->input('type').$result['id'],$request->input('ip'),config('social.key_jwt.time.token'));
            }else{
                $isExpire=true;
            }
            if($isExpire){
                throw new \Exception(__('core.verify'));
            }
            $request->merge($result);
            return $next($request);
        }catch (\Exception $exception){
            throw $exception;
        }
    }
}
