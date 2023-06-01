<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Tiktok extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint=config('social.platform.tiktok.base_api');
        $this->version=config('social.platform.tiktok.version');
        $this->clientId=config('social.platform.tiktok.client_id');
        $this->secretId=config('social.platform.tiktok.client_secret');
        $this->redirect=config('social.platform.tiktok.redirect_uri');
        $this->scope=config('social.platform.tiktok.scope');
        parent::__construct();
    }

    public function generateUrl(array $payload=[],$type='auth'){
        $payload['type']=$type;
        return "https://www.tiktok.com/$this->version/auth/authorize?".http_build_query([
                'client_key'=>$this->clientId,
                'scope'=>$this->implodeScope(),
                'redirect_uri'=>$this->redirect,
                'state'=>CoreHelper::encodeState($payload),
                'response_type'=>'code',
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
            'client_key' => $this->clientId,
            'client_secret' =>$this->secretId,
            'redirect_uri' => $this->redirect,
            'grant_type' => 'authorization_code'
        ];
        return $this->postRequest("$this->endpoint/$this->version/oauth/token/",[
            'Content-Type'=>'application/x-www-form-urlencoded',
            'Cache-Control'=>'no-cache'
        ],$body);
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
