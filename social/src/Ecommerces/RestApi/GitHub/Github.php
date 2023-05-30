<?php

namespace Core\Social\Ecommerces\RestApi\Github;

use Core\Social\Helpers\CoreHelper;

class Github extends BaseApi
{
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

    public function getToken(string $code){

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

    public function getProfile(){
        $url="$this->endpoint/user";
        $header=[
            'Authorization'=>'Bearer '.$this->token
        ];
        return $this->getRequest($url,$header);
    }
}
