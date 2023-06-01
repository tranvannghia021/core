<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class Twitter extends Request implements IEcommerce
{
    protected $codeVerifier;

    public function __construct()
    {
        $this->endpoint = config('social.platform.twitter.base_api');
        $this->version = config('social.platform.twitter.version');
        $this->clientId = config('social.platform.twitter.client_id');
        $this->secretId = config('social.platform.twitter.client_secret');
        $this->redirect = config('social.platform.twitter.redirect_uri');
        $this->scope = config('social.platform.twitter.scope');
        $this->codeVerifier = $this->getCodeVerifier();
        parent::__construct();
    }

    public function auth(array $payload)
    {

    }


    public function getAccessToken(string $code)
    {
        [$code, $codeVerifier] = explode(',', $code);
        return $this->postRequestFormParams("$this->endpoint/$this->version/oauth2/token", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirect,
            'code_verifier' => $codeVerifier
        ]);
    }

    public function refreshToken()
    {
        return $this->postRequestFormParams("$this->endpoint/$this->version/oauth2/token", [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], [
            'refresh_token' => $this->refresh,
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId
        ]);
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/users/me?" . http_build_query([
                'user.fields' => config('social.platform.twitter.field')
            ]), [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    public function generateUrl(array $payload, string $type = 'auth')
    {
        $payload['type'] = $type;
        $payload['code_verifier'] = $this->codeVerifier;
        $url = "https://twitter.com/i/oauth2/authorize?" . http_build_query([
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirect,
                'scope' => $this->implodeScope(' '),
                'state' => CoreHelper::encodeState($payload),
                'code_challenge' => $this->getCodeChallenge(),
                'code_challenge_method' => 'S256'
            ], '', '&', 1);
        return $url;
    }

    protected function getCodeChallenge()
    {
        $hashed = hash('sha256', $this->codeVerifier, true);
        return rtrim(strtr(base64_encode($hashed), '+/', '-_'), '=');
    }

    protected function getCodeVerifier()
    {
        return bin2hex(random_bytes(32));
    }
}