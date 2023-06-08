<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Tiktok extends AEcommerce
{
    protected $urlAuth='https://www.tiktok.com/v2/auth/authorize';
    protected $parameters = [];
    protected $separator = ',';
    public function __construct()
    {
      $this->platform=EnumChannel::TIKTOK;
        parent::__construct();
    }

    public function getStructureAuth(string $state)
    {
        $fields = [
            'client_key' => $this->clientId,
            'scope' => $this->formatScope(),
            'redirect_uri' => $this->redirect,
            'response_type' => 'code',
            'state' => $state,
        ];

        if ($this->usesPKCE()) {
            $fields['code_challenge'] = $this->getCodeChallenge();
            $fields['code_challenge_method'] = $this->getCodeChallengeMethod();
        }

        return array_merge($fields, $this->parameters ?? []);
    }

    public function getAccessToken(string $code)
    {

        return $this->postRequest("$this->endpoint/$this->version/oauth/token/",[
            'Content-Type'=>'application/x-www-form-urlencoded',
            'Cache-Control'=>'no-cache'
        ],$this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        $url="$this->endpoint/$this->version/user/info/".http_build_query(config('social.platform.tiktok.field'));
        return $this->getRequest($url,[
            'Authorization'=>'Bearer '.$this->token
        ]);
    }
}
