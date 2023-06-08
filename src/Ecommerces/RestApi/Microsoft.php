<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Facades\Core;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Microsoft extends AEcommerce
{
    /**
     * @var mixed
     */
    protected $tenant;

    /**
     * @var string[]
     */
    protected $parameters = [
        'response_mode' => 'query'
    ];

    /**
     * @var string
     */
    protected $separator = ' ';

    /**
     * construct Microsoft extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::MICROSOFT;
        $this->tenant = config('social.platform.microsoft.tenant');
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("https://login.microsoftonline.com/$this->tenant/oauth2/v2.0/token", [

            'Content-Type' => 'application/x-www-form-urlencoded'
        ], array_merge([
                'scope' => $this->formatScope(),
            ], $this->buildPayloadToken($code))
        );
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequestFormParams("https://login.microsoftonline.com/$this->tenant/oauth2/v2.0/token", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], array_merge([
            'scope' => $this->formatScope(),
        ], $this->buildPayloadRefresh()));
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/me", [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    /**
     * override method getUrlAuth
     * @return string
     */
    public function getUrlAuth()
    {
        return "https://login.microsoftonline.com/$this->tenant/oauth2/v2.0/authorize";
    }
}