<?php

namespace App\Http\Middleware;

use App\Facades\Core;
use App\Helpers\CoreHelper;
use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Mockery\Exception;

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
            if($isExpire) throw new Exception(__('passwords.verify'));
            $user=app(UserRepository::class)->find($data['id']);
            if(empty($user)){
                throw new Exception(__('passwords.user'));
            }
            Core::setUser($user->toArray());
            return $next($request);
        }catch (Exception $exception){
            throw $exception;
        }
    }
}
