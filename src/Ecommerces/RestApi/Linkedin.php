<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Ecommerces\AEcommerce;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;

class Linkedin extends AEcommerce
{
    protected $parameters = [];
    protected $separator = ' ';

    public function __construct()
    {
        $this->platform = EnumChannel::LINKEDIN;
        parent::__construct();
    }


    public function getAccessToken(string $code)
    {
        return $this->postRequestFormParams("https://www.linkedin.com/oauth/$this->version/accessToken?" . http_build_query(
                $this->buildPayloadToken($code)
            ), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]);
    }

    public function refreshToken()
    {
        // TODO: Implement refreshToken() method.
    }

    public function profile()
    {
        return $this->getRequest("$this->endpoint/$this->version/me?" . http_build_query([
                'projection' => "(id,firstName,lastName,profilePicture(displayImage~:playableStreams))"
            ]), [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    public function email()
    {
        return $this->getRequest("$this->endpoint/$this->version/emailAddress?" . http_build_query([
                'q' => 'members',
                'projection' => '(elements*(handle~))'
            ]), [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    public function getUrlAuth()
    {
        return "https://www.linkedin.com/oauth/$this->version/authorization";
    }

}