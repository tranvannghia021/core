<?php
namespace Core\Social\Service\Contracts;
interface UserContract
{
    public function login(array $payload);

    public function register(array $payload);

    public function verify(array $payload);

    public function verifyForgot(array $payload);

    public function reSendLinkEmail(string $email,string $type);

    public function user();

    public function setUser(array $user):UserContract;

    public function check();

    public function delete(int $id);

    public function changePassword(int $id , string $passwordOld , string $password);

    public function forgotPassword(string $email);

    public function updateUser(array $payload);
}
