<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Google extends AEcommerce
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
     * construct Google extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::GOOGLE;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequest("https://oauth2.$this->endpoint/token", [
            'Content-Type' => 'application/json'
        ], $this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        $url = "https://www.$this->endpoint/oauth2/$this->version/userinfo?alt=json&access_token=" . $this->token;
        return $this->getRequest($url);
    }

    /**
     * override method getUrlAuth
     * @return string
     */
    public function getUrlAuth()
    {
        return "https://accounts.google.com/o/oauth2/$this->version/auth";
    }
}
