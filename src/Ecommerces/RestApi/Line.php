<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Line extends AEcommerce
{

    /**
     * @var string
     */
    protected $urlAuth = 'https://access.line.me/oauth2/v2.1/authorize';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ' ';

    /**
     * construct Line extends AEcommerce
     */
    public function __construct()
    {
        $this->usesPKCE = true;
        $this->platform = EnumChannel::LINE;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $this->buildPayloadRefresh());
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/oauth2/$this->version/userinfo", [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    /**
     * override method getUrlToken
     * @return string
     */
    public function getUrlToken()
    {
        return "$this->endpoint/oauth2/$this->version/token";
    }

    /**
     * verify token in line
     * @return array
     */
    public function verifyToken()
    {
        return $this->postRequestFormParams("$this->endpoint/oauth2/$this->version/verify", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], [
            'id_token' => $this->token,
            'client_id' => $this->clientId
        ]);
    }
}