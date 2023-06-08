<?php

namespace Devtvn\Social\Ecommerces;

use Devtvn\Social\Ecommerces\Constract\IEcommerce;
use Devtvn\Social\Ecommerces\RestApi\Request;
use Devtvn\Social\Helpers\CoreHelper;

abstract class AEcommerce extends Request implements IEcommerce
{
    protected $platform, $usesPKCE = false, $codeVerifier, $separator = ',';

    public function __construct()
    {
        $this->endpoint = config("social.platform.$this->platform.base_api");
        $this->version = config("social.platform.$this->platform.version");
        $this->clientId = config("social.platform.$this->platform.client_id");
        $this->secretId = config("social.platform.$this->platform.client_secret");
        $this->redirect = config("social.platform.$this->platform.redirect_uri");
        $this->scope = config("social.platform.$this->platform.scope");
        $this->codeVerifier = $this->getCodeVerifier();
        parent::__construct();
    }

    public function formatScope()
    {
        return implode($this->separator, $this->scope);
    }


    public function setRefresh(string $refresh): Request
    {
        $this->refresh = $refresh;
        return $this;
    }

    public function setToken(string $token): Request
    {
        $this->token = $token;
        return $this;
    }


    public function getCodeChallenge()
    {
        $hashed = hash('sha256', $this->codeVerifier, true);
        return rtrim(strtr(base64_encode($hashed), '+/', '-_'), '=');
    }

    public function getCodeVerifier()
    {
        return bin2hex(random_bytes(32));
    }

    public function buildLinkAuth(string $state)
    {
        return $this->getUrlAuth() . '?' . http_build_query($this->getStructureAuth($state), '', '&',
                PHP_QUERY_RFC1738);
    }

    public function getUrlAuth()
    {
        return $this->urlAuth;
    }

    public function getUrlToken()
    {
        return $this->urlToken;
    }

    public function getStructureAuth(string $state)
    {
        $fields = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirect,
            'scope' => $this->formatScope(),
            'response_type' => 'code',
            'state' => $state
        ];

        if ($this->usesPKCE()) {
            $fields['code_challenge'] = $this->getCodeChallenge();
            $fields['code_challenge_method'] = $this->getCodeChallengeMethod();
        }

        return array_merge($fields, $this->parameters ?? []);
    }

    public function usesPKCE()
    {
        return $this->usesPKCE;
    }

    public function getCodeChallengeMethod()
    {
        return $this->getCodeChallengeMethod ?? 'S256';
    }

    public function buildPayloadToken(string $code)
    {
        $data = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect,
            'client_id' => $this->clientId,
            'client_secret' => $this->secretId
        ];
        if ($this->usesPKCE()) {
            [$code, $codeVerifier] = explode(',', $code);
            $data['code_verifier'] = $codeVerifier;
            $data['code'] = $code;
        }
        return $data;
    }

    public function buildPayloadRefresh()
    {
        return [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh,
            'redirect_uri' => $this->redirect,
            'client_id' => $this->clientId,
            'client_secret' => $this->secretId
        ];
    }

    public function generateUrl(array $payload = [], $type = 'auth')
    {
        $payload['type'] = $type;
        if ($this->usesPKCE()) {
            $payload['code_verifier'] = $this->codeVerifier;
        }
        return $this->buildLinkAuth(CoreHelper::encodeState($payload));
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function headerAuthBasic(){
        return [
            'Authorization'=>'Basic '.base64_encode($this->clientId . ':' . $this->secretId)
        ];
    }
}