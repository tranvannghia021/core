<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Gitlab extends AEcommerce
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ' ';

    /**
     * construct Gitlab extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::GITLAB;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequest("$this->endpoint/oauth/token?" . http_build_query($this->buildPayloadToken($code)));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequest("$this->endpoint/oauth/token?" . http_build_query($this->buildPayloadRefresh()));
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/api/$this->version/user?" . http_build_query([
                'access_token' => $this->token,
            ]));
    }

    /**
     * override method getUrlAuth
     * @return string
     */
    public function getUrlAuth()
    {
        return "$this->endpoint/oauth/authorize";
    }
}