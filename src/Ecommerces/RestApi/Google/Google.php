<?php

namespace Devtvn\Social\Ecommerces\RestApi\Google;

use Devtvn\Social\Helpers\CoreHelper;

class Google extends BaseApi
{
    public function generateUrl(array $payload=[],$type='auth'){
        $payload['type']=$type;
        return "https://accounts.google.com/o/oauth2/$this->version/auth?".http_build_query([
                'client_id'=>$this->clientId,
                'redirect_uri'=>$this->redirect,
                'state'=>CoreHelper::encodeState($payload),
                'response_type'=>'code',
                'scope'=>$this->implodeScope(),
            ]);
    }

    public function getToken(string $code){
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


    public function getProfile(){
        $url="https://www.$this->endpoint/oauth2/$this->version/userinfo?alt=json&access_token=".$this->token;
        return $this->getRequest($url);

    }
}
