<?php
namespace Core\Social\Ecommerces\RestApi\Facebook;
use Core\Social\Ecommerces\RestApi\Request;

class BaseApi extends Request
{
    protected $endpoint,$version,$token,$clientId,$secretId,$redirect,$scope;
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

    public function setToken(string $token):BaseApi{
        $this->token=$token;
        return $this;
    }

    protected function implodeScope(){
        return implode(',',$this->scope);
    }
}
