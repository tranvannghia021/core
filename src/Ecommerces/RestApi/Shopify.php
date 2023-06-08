<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class Shopify extends AEcommerce
{

    protected $domain;
    protected $parameters = [];
    protected $separator = ',';
    public function __construct()
    {
        $this->platform=EnumChannel::SHOPIFY;
        parent::__construct();
    }
    public function generateUrl(array $payload = [], $type = 'auth')
    {
        if(!isset($payload['domain'])) throw new Exception("Domain is require");
        $this->setParameter($payload['domain']);
        return parent::generateUrl($payload, $type);
    }

    public function getAccessToken(string $code)
    {
        $this->setParameter(Cache::get('domain_'.$code));
        return $this->postRequestFormParams($this->getUrlToken(),[],$this->buildPayloadToken($code));
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
       return $this->getRequest("$this->endpoint/shop.json",[
           'X-Shopify-Access-Token'=>$this->token
       ]);
    }

    public function getUrlAuth()
    {
        return "https://$this->domain/admin/oauth/authorize";
    }
    public function getUrlToken()
    {
        return "https://$this->domain/admin/oauth/access_token";
    }
    public function setParameter($domain):Shopify{
       $this->endpoint= "https://$domain/admin/api/$this->version";
       $this->domain=$domain;
       return $this;
    }
}