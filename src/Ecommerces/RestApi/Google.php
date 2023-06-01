<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Google extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint=config('social.platform.google.base_api');
        $this->version=config('social.platform.google.version');
        $this->clientId=config('social.platform.google.client_id');
        $this->secretId=config('social.platform.google.client_secret');
        $this->redirect=config('social.platform.google.redirect_uri');
        $this->scope=config('social.platform.google.scope');
        parent::__construct();
    }

    public function generateUrl(array $payload=[],$type='auth'){
        $payload['type']=$type;
        return "https://accounts.google.com/o/oauth2/$this->version/auth?".http_build_query([
                'client_id'=>$this->clientId,
                'redirect_uri'=>$this->redirect,
                'state'=>CoreHelper::encodeState($payload),
                'response_type'=>'code',
                'scope'=>$this->implodeScope(' '),
            ]);
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function getAccessToken(string $code)
    {
        $body = [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' =>$this->secretId,
            'redirect_uri' => $this->redirect,
            'grant_type' => 'authorization_code'
        ];
        return $this->postRequest("https://oauth2.$this->endpoint/token",[
            'Content-Type'=>'application/json'
        ],$body);
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        $url="https://www.$this->endpoint/oauth2/$this->version/userinfo?alt=json&access_token=".$this->token;
        return $this->getRequest($url);
    }
}
