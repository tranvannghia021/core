<?php
namespace Devtvn\Social\Service;
interface ICoreService
{
    public function setVariable(array $variable): ICoreService;

    public function generateUrl(array $payload);

    public function auth(array $payload);

    public function usesPKCE();

    public function getToken(array $payload);

    public function getStructure(...$payload);

    public function handleAdditional(array $payload, ...$variable);
}
