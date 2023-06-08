<?php

namespace Devtvn\Social\Ecommerces\Constract;

interface IEcommerce
{
    /**
     * generate url auth with platform
     * @param array $payload
     * @param string $type
     * @return mixed
     */
    public function generateUrl(array $payload, string $type = 'auth');

    /**
     * handle auth callback third party app
     * @param array $payload
     * @return mixed
     */
    public function auth(array $payload);

    /**
     * get token third party app
     * @param string $code
     * @return mixed
     */
    public function getAccessToken(string $code);

    /**
     * refresh token third party app
     * @return mixed
     */
    public function refreshToken();

    /**
     * get profile user third party app
     * @return mixed
     */
    public function profile();

    /**
     * set token global class
     * @param string $token
     * @return mixed
     */
    public function setToken(string $token);

    /**
     * set refresh token global class
     * @param string $refresh
     * @return mixed
     */
    public function setRefresh(string $refresh);

    /**
     * format scope with separator
     * @return mixed
     */
    public function formatScope();

    /**
     * generate code verifier
     * @return mixed
     */
    public function getCodeVerifier();

    /**
     * create code challenge
     * @return mixed
     */
    public function getCodeChallenge();

    /**
     * build structure url auth
     * @param string $state
     * @return mixed
     */
    public function buildLinkAuth(string $state);

    /**
     * get url authentication
     * @return mixed
     */
    public function getUrlAuth();

    /**
     * get url token
     * @return mixed
     */
    public function getUrlToken();

    /**
     * build structure authentication
     * @param string $state
     * @return mixed
     */
    public function getStructureAuth(string $state);

    /**
     * check platform has using auth PKCE
     * @return mixed
     */
    public function usesPKCE();

    /**
     * get alg challenge
     * @return mixed
     */
    public function getCodeChallengeMethod();
}