<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Facades\Core;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Microsoft extends AEcommerce
{
    protected $tenant;
    protected $parameters = [
        'response_mode'=>'query'
    ];
    protected $separator = ' ';
    public function __construct()
    {
   $this->platform=EnumChannel::MICROSOFT;
        $this->tenant = config('social.platform.microsoft.tenant');
        parent::__construct();
    }


    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("https://login.microsoftonline.com/$this->tenant/oauth2/v2.0/token", [

            'Content-Type' => 'application/x-www-form-urlencoded'
        ], array_merge([
                'scope' => $this->formatScope(),
            ],$this->buildPayloadToken($code))
        );
    }

    public function refreshToken()
    {
        return $this->postRequestFormParams("https://login.microsoftonline.com/$this->tenant/oauth2/v2.0/token", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],array_merge([
            'scope' => $this->formatScope(),
        ],$this->buildPayloadRefresh()));
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/me", [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    public function getUrlAuth()
    {
        return "https://login.microsoftonline.com/$this->tenant/oauth2/v2.0/authorize";
    }
}