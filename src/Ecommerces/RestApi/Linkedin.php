<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Linkedin extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint = config('social.platform.linkedin.base_api');
        $this->version = config('social.platform.linkedin.version');
        $this->clientId = config('social.platform.linkedin.client_id');
        $this->secretId = config('social.platform.linkedin.client_secret');
        $this->redirect = config('social.platform.linkedin.redirect_uri');
        $this->scope = config('social.platform.linkedin.scope');
        parent::__construct();
    }

    public function generateUrl(array $payload, string $type = 'auth')
    {
        $payload['type'] = $type;
        return "https://www.linkedin.com/oauth/$this->version/authorization?" . http_build_query([
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirect,
                'state' => CoreHelper::encodeState($payload),
                'scope' => $this->implodeScope(' ')
            ]);
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("https://www.linkedin.com/oauth/$this->version/accessToken?" . http_build_query(
                [
                    'grant_type' => 'authorization_code',
                    'code'=>$code,
                    'client_id'=>$this->clientId,
                    'client_secret'=>$this->secretId,
                    'redirect_uri'=>$this->redirect,
                ]
            ),[
                'Content-Type'=>'application/x-www-form-urlencoded'
        ]);
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/me",[
            'Authorization'=>'Bearer '.$this->token
        ]);
    }
}