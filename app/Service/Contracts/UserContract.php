<?php
namespace App\Service\Contracts;
interface UserContract
{
    public function login(array $payload);

    public function register(array $payload);

    public function verify(array $payload);

    public function reSendLinkEmail(string $email);

    public function user();

    public function setUser(array $user):UserContract;

    public function check();

    public function delete(int $id);
}
