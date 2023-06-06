<?php

namespace Devtvn\Social\Ecommerces\Constract;

interface IEcommerce
{
    public function generateUrl(array $payload, string $type = 'auth');

    public function auth(array $payload);

    public function getAccessToken(string $code);

    public function refreshToken();

    public function profile();

    public function setToken(string $token);

    public function setRefresh(string $refresh);

    public function formatScope();

    public function getCodeVerifier();

    public function getCodeChallenge();

    public function buildLinkAuth(string $state);

    public function getUrlAuth();

    public function getUrlToken();

    public function getStructureAuth(string $state);

    public function usesPKCE();

    public function getCodeChallengeMethod();
}