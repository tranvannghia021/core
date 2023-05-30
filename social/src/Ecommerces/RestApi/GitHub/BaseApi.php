<?php
namespace Core\Social\Ecommerces\RestApi\Github;
use Core\Social\Ecommerces\RestApi\Request;

class BaseApi extends Request
{
    protected $endpoint,$version,$token,$clientId,$secretId,$redirect,$scope;
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

    public function setToken(string $token):BaseApi{
        $this->token=$token;
        return $this;
    }

    protected function implodeScope(){
        return implode(' ',$this->scope);
    }
}
