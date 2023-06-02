<?php

namespace Devtvn\Social\Http\Middleware;

use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Traits\Response;
use Closure;
use Illuminate\Http\Request;
use Mockery\Exception;

class SocialAuthMiddleware
{
    use Response;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $state=$request->input('state');
        if(is_null($state)) throw new Exception(__('core.required_token'));
        try {
            $result=CoreHelper::decodeState($state);
            $request->merge($result);
            return $next($request);
        }catch (\Exception $exception){

            throw new \Exception('Signature verification failed');
        }

    }
}
