<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class Shopify extends AEcommerce
{
    /**
     * @var mixed
     */
    protected $domain;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Shopify extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::SHOPIFY;
        parent::__construct();
    }

    /**
     * override method generateUrl
     * @param array $payload
     * @param $type
     * @return mixed|string
     */
    public function generateUrl(array $payload = [], $type = 'auth')
    {
        if (!isset($payload['domain'])) {
            throw new Exception("Domain is require");
        }
        $this->setParameter($payload['domain']);
        return parent::generateUrl($payload, $type);
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        $this->setParameter(Cache::get('domain_' . $code));
        return $this->postRequestFormParams($this->getUrlToken(), [], $this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/shop.json", [
            'X-Shopify-Access-Token' => $this->token
        ]);
    }

    /**
     * override method getUrlAuth
     * @return string
     */
    public function getUrlAuth()
    {
        return "https://$this->domain/admin/oauth/authorize";
    }

    /**
     * override method getUrlToken
     * @return string
     */
    public function getUrlToken()
    {
        return "https://$this->domain/admin/oauth/access_token";
    }

    /**
     * ready endpoint shopify global class
     * @param $domain
     * @return $this
     */
    public function setParameter($domain): Shopify
    {
        $this->endpoint = "https://$domain/admin/api/$this->version";
        $this->domain = $domain;
        return $this;
    }
}