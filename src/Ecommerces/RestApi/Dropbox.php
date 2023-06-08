<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\EnumChannel;
use Mockery\Exception;

class Dropbox extends AEcommerce
{
    /**
     * @var string
     */
    protected $urlAuth = 'https://www.dropbox.com/oauth2/authorize';

    /**
     * @var string
     */
    protected $urlToken = '';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $separator = ',';

    /**
     * construct Dropbox extends AEcommerce
     */
    public function __construct()
    {
        $this->platform = EnumChannel::DROPBOX;
        $this->usesPKCE = true;
        parent::__construct();
    }

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("$this->endpoint/oauth2/token", [], $this->buildPayloadToken($code));
    }

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken()
    {
        return $this->postRequestFormParams("$this->endpoint/oauth2/token", [], $this->buildPayloadRefresh());
    }

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile()
    {
        try {
            $res = $this->_client->request("POST", "$this->endpoint/$this->version/users/get_current_account", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json'
                ],
                'body' => 'null'
            ]);
            return [
                'status' => true,
                'data' => json_decode($res->getBody()->getContents(), true)
            ];
        } catch (Exception $exception) {
            return [
                'status' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }
}