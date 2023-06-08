<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Instagram extends AEcommerce
{
    /**
     * @var string
     */
    protected $urlAuth = 'https://api.instagram.com/oauth/authorize';

    /**
     * @var string
     */
    protected $urlToken = 'https://api.instagram.com/oauth/access_token';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Instagram extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::INSTAGRAM_BASIC;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(), [], $this->buildPayloadToken($code));
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
        return $this->getRequest("$this->endpoint/me?" . http_build_query([
                'fields' => implode(',', config('social.platform.instagram_basic.field')),
                'access_token' => $this->token
            ]));
    }
}