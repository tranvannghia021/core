<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Pinterest extends AEcommerce
{

    protected $urlAuth='https://www.pinterest.com/oauth/';
    protected $parameters = [];
    protected $separator = ',';
    public function __construct()
    {
        $this->platform=EnumChannel::PINTEREST;
        parent::__construct();
    }
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(),array_merge([
            'Content-Type'=>'application/x-www-form-urlencoded'
        ],$this->headerAuthBasic()),$this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        return $this->postRequestFormParams($this->getUrlToken(),array_merge([
            'Content-Type'=>'application/x-www-form-urlencoded'
        ],$this->headerAuthBasic()),$this->buildPayloadRefresh());
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/user_account",[
            'Authorization'=>'Bearer '.$this->token
        ]);
    }

    public function getUrlAuth()
    {
        return "$this->endpoint/$this->version/oauth/token";
    }

}