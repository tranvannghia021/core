<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Bitbucket extends AEcommerce
{
    /**
     * @var string
     */
    protected $urlAuth = 'https://bitbucket.org/site/oauth2/authorize';

    /**
     * @var string
     */
    protected $urlToken = 'https://bitbucket.org/site/oauth2/access_token';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct bitbucket extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::BITBUCKET;
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
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ], $this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequestFormParams($this->getUrlToken(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ], $this->buildPayloadRefresh());
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/user?" . http_build_query([
                'access_token' => $this->token
            ]));
    }

    /**
     * get email user
     * @return array
     */
    public function email()
    {
        return $this->getRequest("$this->endpoint/$this->version/user/emails?" . http_build_query([
                'access_token' => $this->token
            ]));
    }

}