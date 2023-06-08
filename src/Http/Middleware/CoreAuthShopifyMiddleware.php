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
        if ($request->header('X-Shopify-Hmac-SHA256') !== null && !$this->verifyHmac($request)) {
            throw new Exception("Auth is invalid");
        }
        Cache::put('domain_'.$request->input('code'),$request->input('shop'),60);
        return $next($request);
    }

    public function verifyHmac($request)
    {
        return hash_equals(
            base64_encode(
                hash_hmac('sha256',
                    file_get_contents('php://input'),
                    config('social.platform.shopify.client_secret'), true)),
            $request->header('X-Shopify-Hmac-SHA256'));
    }
}