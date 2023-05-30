<?php
namespace Core\Social\Ecommerces\RestApi\Google;
use Core\Social\Ecommerces\RestApi\Request;

class BaseApi extends Request
{
    protected $endpoint,$version,$token,$clientId,$secretId,$redirect,$scope;
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

    public function setToken(string $token): BaseApi
    {
        $this->token=$token;
        return $this;
    }

    protected function implodeScope(){
        return implode(' ',$this->scope);
    }
}
