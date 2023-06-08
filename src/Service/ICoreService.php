<?php

namespace Devtvn\Social\Service;

interface ICoreService
{
    /**
     * set variable global
     * @param array $variable
     * @return $this
     */
    public function setVariable(array $variable): ICoreService;

    /**
     * generate url authentication with platform
     * @param array $payload
     * @return mixed
     */
    public function generateUrl(array $payload);

    /**
     * handle auth callback
     * @param array $payload
     * @return mixed
     */
    public function auth(array $payload);

    /**
     * check platform has using authentication with platform
     * @return mixed
     */
    public function usesPKCE();

    /**
     * sub method get token
     * @param array $payload
     * @return mixed
     */
    public function getToken(array $payload);

    /**
     * build structure ready save databases
     * @param ...$payload
     * @return mixed
     */
    public function getStructure(...$payload);

    /**
     * handle logic after api profile
     * @param array $payload
     * @param ...$variable
     * @return mixed
     */
    public function handleAdditional(array $payload, ...$variable);

    /**
     * handel before install
     * @param ...$payload
     * @return mixed
     */
    public function beforeInstall(...$payload);

    /**
     * handel between call api token and profile
     * @param ...$payload
     * @return mixed
     */
    public function middleInstallBothTokenAndProfile(...$payload);

    /**
     * handle after install
     * @param ...$payload
     * @return mixed
     */
    public function afterInstall(...$payload);

}
