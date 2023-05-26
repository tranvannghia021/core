<?php

namespace App\Http\Middleware;

use App\Facades\Core;
use App\Helpers\CoreHelper;
use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Mockery\Exception;

class GlobalJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('Authorization', null);
        try {
            if (!is_null($token)) {
                $data = CoreHelper::decodeJwt($token);
                $isExpire = CoreHelper::expireToken($data['expire']);
                if (!$isExpire && !Core::check()) {
                    $res = app(UserRepository::class)->find($data['id']);
                    if (!empty($res)) {
                        Core::setUser($res->toArray());
                    }
                }
            }
        } catch (Exception $exception) {
            if (!is_null($token)) {
                $data = CoreHelper::decodeJwt($token, true);
                $isExpire = CoreHelper::expireToken($data['expire']);
                if (!$isExpire && !Core::check()) {
                    $res = app(UserRepository::class)->find($data['id']);
                    if (!empty($res)) {
                        Core::setUser($res->toArray());
                    }
                }
            }
        }
        return $next($request);
    }
}
