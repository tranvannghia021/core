<?php

namespace Devtvn\Social\Service\SocialPlatform;


use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Devtvn\Social\Service\ACoreService;
use Devtvn\Social\Service\ICoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class GithubService extends ACoreService
{
    use Response;

    public function __construct()
    {
        $this->platform = Social::driver(EnumChannel::GITHUB);
        parent::__construct();

    }

    public function getStructure(...$payload)
    {
        [$token, $user] = $payload;
        return [
            'internal_id' => (string)$user['data']['id'],
            'first_name' => $user['data']['name'],
            'last_name' => @$user['data']['last_name'] ?? '',
            'avatar' => $user['data']['avatar_url'],
            'email' => @$user['data']['email'] ?? $user['data']['login'] . '@gmail.com',
            'email_verified_at' => now(),
            'platform' => EnumChannel::GITHUB,
            'password' => Hash::make(123456789),
            'status' => true,
            'access_token' => @$token['data']['access_token'],
            'refresh_token' => @$token['data']['refresh_token'],
            'expire_token' => date("Y-m-d H:i:s", time() + @$token['data']['expires_in'] ?? 0),
            'address' => @$user['data']['location'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function handleAdditional(array $payload, ...$variable)
    {
        // TODO: Implement handleAdditional() method.
    }
}
