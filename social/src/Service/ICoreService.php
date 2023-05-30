<?php
namespace Core\Social\Service;
interface ICoreService
{
    public function setVariable(array $variable): ICoreService;

    public function generateUrl(array $payload);

    public function auth(array $payload);
}
