<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Reddit extends AEcommerce
{

    protected $urlAuth='https://www.reddit.com/api/v1/authorize';
    protected $urlToken='https://www.reddit.com/api/v1/access_token';
    protected $parameters = [
        'duration'=>'permanent' // permanent|temporary
    ];
    protected $separator = ' ';
    public function __construct()
    {
        $this->platform=EnumChannel::REDDIT;
        parent::__construct();
    }


    public function getAccessToken(string $code)
    {
        return $this->postRequest($this->getUrlToken() .'?'.
            http_build_query($this->buildPayloadToken($code)),
            $this->headerAuthBasic());
    }

    public function refreshToken()
    {
        return $this->postRequest($this->getUrlToken().'?'.http_build_query($this->buildPayloadRefresh()),$this->headerAuthBasic());
    }

    public function profile()
    {
       return $this->getRequest("$this->endpoint/api/$this->version/me",[
           'Authorization'=>'Bearer '.$this->token,
           'Content-Type'=>'application/json',
           'User-Agent'=>request()->userAgent()
       ]);
    }
}