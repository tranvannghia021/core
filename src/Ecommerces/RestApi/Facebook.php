<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\CoreHelper;

class Facebook extends Request implements IEcommerce
{
    public function __construct()
    {
                $this->endpoint=config('social.platform.facebook.base_api');
                $this->version=config('social.platform.facebook.version');
                $this->clientId=config('social.platform.facebook.client_id');
                $this->secretId=config('social.platform.facebook.client_secret');
                $this->redirect=config('social.platform.facebook.redirect_uri');
                $this->scope=config('social.platform.facebook.scope');
                parent::__construct();
    }

    public function generateUrl($payload ,$type = 'auth'){
        $payload['type']=$type;
        return "https://www.facebook.com/{$this->version}/dialog/oauth?".http_build_query(
                [
                    "client_id"=>$this->clientId,
                    'redirect_uri'=>$this->redirect,
                    'response_type'=>'code',
                    'display'=>'popup',
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
        $url="$this->endpoint/$this->version/oauth/access_token?".http_build_query([
                'client_id'=>$this->clientId,
                'redirect_uri'=>$this->redirect,
                'client_secret'=>$this->secretId,
                'code'=>$code,
            ]);
        return $this->getRequest($url);
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        $url="$this->endpoint/$this->version/me?".http_build_query([
                'access_token'=>$this->token,
                'fields'=>implode(',',config('social.platform.facebook.field'))
            ]);
        return $this->getRequest($url);
    }
}
