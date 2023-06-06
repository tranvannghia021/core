<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Google extends AEcommerce
{
    protected $parameters = [];
    protected $separator = ' ';

    public function __construct()
    {
        $this->platform = EnumChannel::GOOGLE;
        parent::__construct();
    }

    public function generateUrl(array $payload = [], $type = 'auth')
    {
        $payload['type'] = $type;
        return $this->buildLinkAuth(CoreHelper::encodeState($payload));
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequest("https://oauth2.$this->endpoint/token", [
            'Content-Type' => 'application/json'
        ], $this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        $url = "https://www.$this->endpoint/oauth2/$this->version/userinfo?alt=json&access_token=" . $this->token;
        return $this->getRequest($url);
    }

    public function getUrlAuth()
    {
        return "https://accounts.google.com/o/oauth2/$this->version/auth";
    }
}
