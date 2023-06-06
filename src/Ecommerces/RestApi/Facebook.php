<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Facebook extends AEcommerce
{
    protected $parameters = [
        'display' => 'popup'
    ];
    protected $separator = ',';

    public function __construct()
    {
        $this->platform = EnumChannel::FACEBOOK;
        parent::__construct();
    }


    public function getAccessToken(string $code)
    {
        return $this->getRequest("$this->endpoint/$this->version/oauth/access_token?" . http_build_query($this->buildPayloadToken($code)));
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        $url = "$this->endpoint/$this->version/me?" . http_build_query([
                'access_token' => $this->token,
                'fields' => implode(',', config('social.platform.facebook.field'))
            ]);
        return $this->getRequest($url);
    }

    public function getUrlAuth()
    {
        return "https://www.facebook.com/{$this->version}/dialog/oauth";
    }

}
