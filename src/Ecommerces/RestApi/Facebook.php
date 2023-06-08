<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Facebook extends AEcommerce
{
    /**
     * @var string[]
     */
    protected $parameters = [
        'display' => 'popup'
    ];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Facebook extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::FACEBOOK;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->getRequest("$this->endpoint/$this->version/oauth/access_token?" . http_build_query($this->buildPayloadToken($code)));
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
        $url = "$this->endpoint/$this->version/me?" . http_build_query([
                'access_token' => $this->token,
                'fields' => implode(',', config('social.platform.facebook.field'))
            ]);
        return $this->getRequest($url);
    }

    /**
     * override method getUrlAuth
     * @return string
     */
    public function getUrlAuth()
    {
        return "https://www.facebook.com/{$this->version}/dialog/oauth";
    }

}
