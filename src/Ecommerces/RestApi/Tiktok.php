<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Tiktok extends AEcommerce
{
    /**
     * @var string
     */
    protected $urlAuth = 'https://www.tiktok.com/v2/auth/authorize/';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Tiktok extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::TIKTOK;
        parent::__construct();
    }


    /**
     * build structure authentication
     * @param string $state
     * @return mixed|array
     */
    public function getStructureAuth(string $state)
    {
        $fields = [
            'client_key' => $this->clientId,
            'response_type' => 'code',
            'scope' => $this->formatScope(),
            'redirect_uri' => $this->redirect,
            'state' => $state,
        ];

        if ($this->usesPKCE()) {
            $fields['code_challenge'] = $this->getCodeChallenge();
            $fields['code_challenge_method'] = $this->getCodeChallengeMethod();
        }

        return array_merge($fields, $this->parameters ?? []);
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {

        return $this->postRequest("$this->endpoint/$this->version/oauth/token/", [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cache-Control' => 'no-cache'
        ], $this->buildPayloadToken($code));
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
        $url = "$this->endpoint/$this->version/user/info/" . http_build_query(config('social.platform.tiktok.field'));
        return $this->getRequest($url, [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }
}
