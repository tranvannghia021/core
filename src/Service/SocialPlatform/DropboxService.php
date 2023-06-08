<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\ACoreService;
use Illuminate\Support\Facades\Hash;

class DropboxService extends ACoreService
{
    public function __construct()
    {
        $this->platform = Social::driver(EnumChannel::DROPBOX);
        $this->usesPKCE = true;
        parent::__construct();
    }

    public function getStructure(...$payload)
    {
        [$token, $user] = $payload;
        return [
            'internal_id' => (string)$user['data']['account_id'],
            'first_name' => @$user['data']['name']['given_name'],
            'last_name' => @$user['data']['name']['surname'],
            'email' => @$user['data']['email'],
            'email_verified_at' => now(),
            'platform' => EnumChannel::DROPBOX,
            'avatar' => null,
            'password' => Hash::make(123456789),
            'status' => true,
            'access_token' => @$token['data']['access_token'],
            'expire_token' => date("Y-m-d H:i:s", time() + @$token['data']['expires_in'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function handleAdditional(array $payload, ...$variable)
    {
        // TODO: Implement handleAdditional() method.
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