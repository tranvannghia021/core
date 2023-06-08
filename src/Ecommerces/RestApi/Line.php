<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Line extends AEcommerce
{

    protected $urlAuth = 'https://access.line.me/oauth2/v2.1/authorize';
    protected $parameters = [];
    protected $separator = ' ';

    public function __construct()
    {
        $this->usesPKCE = true;
        $this->platform = EnumChannel::LINE;
        parent::__construct();
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $this->buildPayloadRefresh());
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/oauth2/$this->version/userinfo", [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    public function getUrlToken()
    {
        return "$this->endpoint/oauth2/$this->version/token";
    }

    public function verifyToken(){
        return $this->postRequestFormParams("$this->endpoint/oauth2/$this->version/verify",[
            'Content-Type'=>'application/x-www-form-urlencoded'
        ],[
            'id_token'=>$this->token,
            'client_id'=>$this->clientId
        ]);
    }
}