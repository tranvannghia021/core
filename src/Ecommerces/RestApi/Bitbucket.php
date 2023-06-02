<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Bitbucket extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint = config('social.platform.bitbucket.base_api');
        $this->version = config('social.platform.bitbucket.version');
        $this->clientId = config('social.platform.bitbucket.client_id');
        $this->secretId = config('social.platform.bitbucket.client_secret');
        $this->redirect = config('social.platform.bitbucket.redirect_uri');
        $this->scope = config('social.platform.bitbucket.scope');
        parent::__construct();
    }

    public function generateUrl(array $payload, string $type = 'auth')
    {
        $payload['type'] = $type;
        return "https://bitbucket.org/site/oauth2/authorize?" . http_build_query([
                'client_id' => $this->clientId,
                'scope' => $this->implodeScope(),
                'redirect_uri' => $this->redirect,
                'state' => CoreHelper::encodeState($payload),
                'response_type' => 'code',
            ], '', '&', PHP_QUERY_RFC1738);
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("https://bitbucket.org/site/oauth2/access_token", [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ], [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirect
        ]);
    }

    public function refreshToken()
    {
        return $this->postRequestFormParams("https://bitbucket.org/site/oauth2/access_token", [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ], [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh,
        ]);
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/user?" . http_build_query([
                'access_token' => $this->token
            ]));
    }

    public function email()
    {
        return $this->getRequest("$this->endpoint/$this->version/user/emails?" . http_build_query([
                'access_token' => $this->token
            ]));
    }
}