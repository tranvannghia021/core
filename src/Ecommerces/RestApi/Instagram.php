<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Instagram extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint = config('social.platform.instagram_basic.base_api');
        $this->version = config('social.platform.instagram_basic.version');
        $this->clientId = config('social.platform.instagram_basic.client_id');
        $this->secretId = config('social.platform.instagram_basic.client_secret');
        $this->redirect = config('social.platform.instagram_basic.redirect_uri');
        $this->scope = config('social.platform.instagram_basic.scope');
        parent::__construct();
    }

    public function generateUrl(array $payload, string $type = 'auth')
    {
        $payload['type'] = $type;
        return "https://api.instagram.com/oauth/authorize?" . http_build_query([
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirect,
                'scope' => $this->implodeScope(),
                'response_type' => 'code',
                'state' => CoreHelper::encodeState($payload)
            ]);
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("https://api.instagram.com/oauth/access_token",[], [
            'client_id' => $this->clientId,
            'client_secret' => $this->secretId,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect
        ]);
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