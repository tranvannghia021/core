<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\ACoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class LineService extends ACoreService
{

    use Response;

    public function __construct()
    {
        $this->platform = Social::driver(EnumChannel::LINE);
        $this->usesPKCE = true;
        parent::__construct();
    }

    public function getStructure(...$payload)
    {
        [$token, $user,$additions] = $payload;
        return [
            'internal_id' => (string)@$user['data']['sub'] ?? $additions['data']['sub'],
            'first_name' => @$user['data']['name'] ?? @$additions['data']['name'],
            'email' => @$additions['data']['email'],
            'email_verified_at' => now(),
            'platform' => EnumChannel::LINE,
            'avatar' =>@$user['data']['picture'] ??  @$additions['data']['picture'],
            'password' => Hash::make(123456789),
            'status' => true,
            'access_token' => @$token['data']['access_token'],
            'refresh_token' => @$token['data']['refresh_token'],
            'expire_token' => date("Y-m-d H:i:s", time() + @$token['data']['expires_in'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function handleAdditional(array $payload, ...$variable)
    {
        [$token,$user]=$variable;
        return Social::driver(EnumChannel::LINE)->setToken($token['data']['id_token'])->verifyToken();
    }

    public function beforeInstall(...$payload)
    {
        // TODO: Implement beforeInstall() method.
    }

    public function middleInstallBothTokenAndProfile(...$payload)
    {
        // TODO: Implement middleInstallBothTokenAndProfile() method.
    }

    public function afterInstall(...$payload)
    {
        // TODO: Implement afterInstall() method.
    }
}