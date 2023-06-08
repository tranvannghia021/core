<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Github extends AEcommerce
{
    /**
     * @var string
     */
    protected $urlAuth = 'https://github.com/login/oauth/authorize';

    /**
     * @var string
     */
    protected $urlToken = 'https://github.com/login/oauth/access_token';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Github extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::GITHUB;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        $url = $this->getUrlToken() . "?" . http_build_query($this->buildPayloadToken($code));
        $header = [
            'Accept' => 'application/json'
        ];
        return $this->postRequest($url, $header);
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequest($this->getUrlToken() . "?" . http_build_query($this->buildPayloadRefresh()));
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        $url = "$this->endpoint/user";
        $header = [
            'Authorization' => 'Bearer ' . $this->token
        ];
        return $this->getRequest($url, $header);
    }
}
