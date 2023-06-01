<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Github extends Request implements IEcommerce
{
    public function __construct()
    {
        $this->endpoint=config('social.platform.github.base_api');
        $this->version=config('social.platform.github.version');
        $this->clientId=config('social.platform.github.client_id');
        $this->secretId=config('social.platform.github.client_secret');
        $this->redirect=config('social.platform.github.redirect_uri');
        $this->scope=config('social.platform.github.scope');
        parent::__construct();
    }
    public function generateUrl(array $payload=[],$type='auth'){
        $payload['type']=$type;
        return "https://github.com/login/oauth/authorize?".http_build_query(
                [
                    "client_id"=>$this->clientId,
                    'redirect_uri'=>$this->redirect,
                    'scope'=>$this->implodeScope(),
                    'state'=>CoreHelper::encodeState($payload)
                ]
            );

    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }

    public function getAccessToken(string $code)
    {
        $url="https://github.com/login/oauth/access_token?".http_build_query([
                'client_id'=>$this->clientId,
                'redirect_uri'=>$this->redirect,
                'client_secret'=>$this->secretId,
                'code'=>$code,
            ]);
        $header=[
            'Accept'=>'application/json'
        ];
        return $this->postRequest($url,$header);
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        $url="$this->endpoint/user";
        $header=[
            'Authorization'=>'Bearer '.$this->token
        ];
        return $this->getRequest($url,$header);
    }
}
