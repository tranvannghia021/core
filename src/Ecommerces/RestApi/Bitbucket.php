<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Bitbucket extends AEcommerce
{
    protected $urlAuth = 'https://bitbucket.org/site/oauth2/authorize';
    protected $urlToken = 'https://bitbucket.org/site/oauth2/access_token';
    protected $parameters = [];
    protected $separator = ',';

    public function __construct()
    {
        $this->platform = EnumChannel::BITBUCKET;
        parent::__construct();
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ], $this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ], $this->buildPayloadRefresh());
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/user?" . http_build_query([
                'access_token' => $this->token
            ]));
    }

    public function email()
    {
        return $this->getRequest("$this->endpoint/$this->version/user/emails?" . http_build_query([
                'access_token' => $this->token
            ]));
    }

}