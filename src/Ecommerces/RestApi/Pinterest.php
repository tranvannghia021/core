<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Pinterest extends AEcommerce
{

    /**
     * @var string
     */
    protected $urlAuth='https://www.pinterest.com/oauth/';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Pinterest extends AEcommerce
     */
    public function __construct()
    {
        $this->platform=EnumChannel::PINTEREST;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(),array_merge([
            'Content-Type'=>'application/x-www-form-urlencoded'
        ],$this->headerAuthBasic()),$this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequestFormParams($this->getUrlToken(),array_merge([
            'Content-Type'=>'application/x-www-form-urlencoded'
        ],$this->headerAuthBasic()),$this->buildPayloadRefresh());
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/user_account",[
            'Authorization'=>'Bearer '.$this->token
        ]);
    }

    /**
     * override method getUrlAuth
     * @return string
     */
    public function getUrlAuth()
    {
        return "$this->endpoint/$this->version/oauth/token";
    }

}