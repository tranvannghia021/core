<?php
namespace Devtvn\Social\Service\Contracts;
interface CoreContract
{
    /**
     * login user
     * @param array $payload
     * @return mixed
     */
    public function login(array $payload);

    /**
     * register user
     * @param array $payload
     * @return mixed
     */
    public function register(array $payload);

    /**
     * verify callback
     * @param array $payload
     * @return mixed
     */
    public function verify(array $payload);

    /**
     * verify callback forgot
     * @param array $payload
     * @return mixed
     */
    public function verifyForgot(array $payload);

    /**
     * re-send link to email
     * @param string $email
     * @param string $type
     * @return mixed
     */
    public function reSendLinkEmail(string $email,string $type);

    /**
     * get info user
     * @return mixed
     */
    public function user();

    /**
     * set user global
     * @param array $user
     * @return CoreContract
     */
    public function setUser(array $user):CoreContract;

    /**
     * check has user
     * @return mixed
     */
    public function check();

    /**
     * delete user
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * change password
     * @param int $id
     * @param string $passwordOld
     * @param string $password
     * @return mixed
     */
    public function changePassword(int $id , string $passwordOld , string $password);

    /**
     * forgot password
     * @param string $email
     * @return mixed
     */
    public function forgotPassword(string $email);

    /**
     * update user
     * @param array $payload
     * @return mixed
     */
    public function updateUser(array $payload);
}
