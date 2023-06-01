<?php

namespace Devtvn\Social\Ecommerces\RestApi;

interface IEcommerce
{
    public function generateUrl(array $payload,string $type='auth');

    public function auth(array $payload);

    public function getAccessToken(string $code);

    public function refreshToken();

    public function profile();
}