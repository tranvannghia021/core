<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Gitlab extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint = config('social.platform.gitlab.base_api');
        $this->version = config('social.platform.gitlab.version');
        $this->clientId = config('social.platform.gitlab.client_id');
        $this->secretId = config('social.platform.gitlab.client_secret');
        $this->redirect = config('social.platform.gitlab.redirect_uri');
        $this->scope = config('social.platform.gitlab.scope');
        parent::__construct();
    }

    public function generateUrl(array $payload, string $type = 'auth')
    {
        $payload['type'] = $type;
        return "$this->endpoint/oauth/authorize?" . http_build_query([
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirect,
                'scope' => $this->implodeScope(' '),
                'response_type' => 'code',
                'state'=>CoreHelper::encodeState($payload)
            ], '', '&', PHP_QUERY_RFC1738);
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequest("$this->endpoint/oauth/token?" . http_build_query([
                'client_id' => $this->clientId,
                'client_secret' => $this->secretId,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirect
            ]));
    }

    public function refreshToken()
    {
        return $this->postRequest("$this->endpoint/oauth/token?" . http_build_query([
                'client_id' => $this->clientId,
                'client_secret' => $this->secretId,
                'refresh_token' => $this->refresh,
                'grant_type' => 'refresh_token',
                'redirect_uri' => $this->redirect
            ]));
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/api/$this->version/user?" . http_build_query([
                'access_token' => $this->token,
            ]));
    }
}