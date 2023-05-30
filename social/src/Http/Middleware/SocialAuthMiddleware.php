<?php

namespace Core\Social\Http\Middleware;

use Core\Social\Helpers\CoreHelper;
use Core\Social\Traits\Response;
use Closure;
use Illuminate\Http\Request;

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
        try {
            $result=CoreHelper::decodeState($state);
            $request->merge($result);
            return $next($request);
        }catch (\Exception $exception){

            throw new \Exception('Signature verification failed');
        }

    }
}
