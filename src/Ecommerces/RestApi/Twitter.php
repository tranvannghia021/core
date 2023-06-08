<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class Twitter extends AEcommerce
{
    /**
     * @var string
     */
    protected $urlAuth = 'https://twitter.com/i/oauth2/authorize';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ' ';

    /**
     * construct Twitter extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::TWITTER;
        $this->usesPKCE = true;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("$this->endpoint/$this->version/oauth2/token", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequestFormParams("$this->endpoint/$this->version/oauth2/token", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], $this->buildPayloadRefresh());
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/users/me?" . http_build_query([
                'user.fields' => config('social.platform.twitter.field')
            ]), [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

}