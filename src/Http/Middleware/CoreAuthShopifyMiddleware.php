<?php

namespace Devtvn\Social\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class CoreAuthShopifyMiddleware
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

        if ($request->input('hmac') !== null && !$this->verifyHmac($request)) {
            throw new Exception("Auth is invalid");
        }
        Cache::put('domain_'.$request->input('code'),$request->input('shop'),60);
        return $next($request);
    }

    public function verifyHmac($request)
    {
        $body=$request->only(["code","shop","host","timestamp","state"]);
        ksort($body);
        return$request->input("hmac") === hash_hmac('sha256', http_build_query($body),config('social.platform.shopify.client_secret'));
    }
}