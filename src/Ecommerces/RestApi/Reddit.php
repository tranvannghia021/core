<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;

class Reddit extends AEcommerce
{

    /**
     * @var string
     */
    protected $urlAuth = 'https://www.reddit.com/api/v1/authorize';

    /**
     * @var string
     */
    protected $urlToken = 'https://www.reddit.com/api/v1/access_token';

    /**
     * @var string[]
     */
    protected $parameters = [
        'duration' => 'permanent' // permanent|temporary
    ];

    /**
     * @var string
     */
    protected $separator = ' ';

    /**
     * construct Reddit extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::REDDIT;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequest($this->getUrlToken() . '?' .
            http_build_query($this->buildPayloadToken($code)),
            $this->headerAuthBasic());
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequest($this->getUrlToken() . '?' . http_build_query($this->buildPayloadRefresh()),
            $this->headerAuthBasic());
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        return $this->getRequest("$this->endpoint/api/$this->version/me", [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
            'User-Agent' => request()->userAgent()
        ]);
    }
}