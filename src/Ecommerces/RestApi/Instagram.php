<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Instagram extends AEcommerce
{
    protected $urlAuth = 'https://api.instagram.com/oauth/authorize';
    protected $urlToken = 'https://api.instagram.com/oauth/access_token';
    protected $parameters = [];
    protected $separator = ',';

    public function __construct()
    {
        $this->platform = EnumChannel::INSTAGRAM_BASIC;
        parent::__construct();
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams($this->getUrlToken(), [], $this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/me?" . http_build_query([
                'fields' => implode(',', config('social.platform.instagram_basic.field')),
                'access_token' => $this->token
            ]));
    }
}