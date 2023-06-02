<?php

namespace Devtvn\Social\Http\Middleware;

use Devtvn\Social\Facades\Core;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;

class RefreshMiddleware
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
            $data=CoreHelper::decodeJwt($token,true);
            $isExpire=CoreHelper::expireToken($data['expire']);
            if($isExpire) throw new \Exception(__('core.verify'));
            $user=app(UserRepository::class)->find($data['id']);
            if(empty($user)){
                throw new \Exception(__('core.user'));
            }
            Core::setUser($user->toArray());
            return $next($request);
        }catch (\Exception $exception){
            throw $exception;
        }
    }
}
