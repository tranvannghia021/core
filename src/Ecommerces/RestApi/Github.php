<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Github extends AEcommerce
{
    protected $urlAuth = 'https://github.com/login/oauth/authorize';
    protected $urlToken = 'https://github.com/login/oauth/access_token';
    protected $parameters = [];
    protected $separator = ',';

    public function __construct()
    {
        $this->platform = EnumChannel::GITHUB;
        parent::__construct();
    }


    public function getAccessToken(string $code)
    {
        $url = $this->getUrlToken() . "?" . http_build_query($this->buildPayloadToken($code));
        $header = [
            'Accept' => 'application/json'
        ];
        return $this->postRequest($url, $header);
    }

    public function refreshToken()
    {
        return $this->postRequest($this->getUrlToken() . "?" . http_build_query($this->buildPayloadRefresh()));
    }

    public function profile()
    {
        $url = "$this->endpoint/user";
        $header = [
            'Authorization' => 'Bearer ' . $this->token
        ];
        return $this->getRequest($url, $header);
    }
}
