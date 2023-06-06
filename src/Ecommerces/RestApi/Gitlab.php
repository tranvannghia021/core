<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Gitlab extends AEcommerce
{
    protected $parameters = [];
    protected $separator = ' ';

    public function __construct()
    {
        $this->platform = EnumChannel::GITLAB;
        parent::__construct();
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequest("$this->endpoint/oauth/token?" . http_build_query($this->buildPayloadToken($code)));
    }

    public function refreshToken()
    {
        return $this->postRequest("$this->endpoint/oauth/token?" . http_build_query($this->buildPayloadRefresh()));
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/api/$this->version/user?" . http_build_query([
                'access_token' => $this->token,
            ]));
    }

    public function getUrlAuth()
    {
        return "$this->endpoint/oauth/authorize";
    }
}