<?php

namespace Devtvn\Social\Ecommerces\RestApi\Facebook;

use Devtvn\Social\Helpers\CoreHelper;

class Facebook extends BaseApi
{
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

    public function getToken(string $code){
        $url="$this->endpoint/$this->version/oauth/access_token?".http_build_query([
                'client_id'=>$this->clientId,
                'redirect_uri'=>$this->redirect,
                'client_secret'=>$this->secretId,
                'code'=>$code,
            ]);
        return $this->getRequest($url);
    }

    public function getProfile(){
        $url="$this->endpoint/$this->version/me?".http_build_query([
                'access_token'=>$this->token,
                'fields'=>implode(',',[
                    'id',
                    'name',
                    'first_name',
                    'last_name',
                    'email',
                    'birthday',
                    'gender',
                    'hometown',
                    'location',
                    'picture',
                ])
            ]);
        return $this->getRequest($url);
    }

}
