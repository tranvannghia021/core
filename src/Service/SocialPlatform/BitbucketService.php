<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\ACoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class BitbucketService extends ACoreService
{
    use Response;

    public function __construct()
    {
        $this->platform = Social::driver(EnumChannel::BITBUCKET);
        parent::__construct();
    }

    /**
     * build structure ready save databases
     * @param ...$payload
     * @return array
     */
    public function getStructure(...$payload)
    {
        [$token, $user, $additions] = $payload;
        return [
            'internal_id' => (string)$user['data']['account_id'],
            'first_name' => @$user['data']['display_name'] ?? @$user['data']['username'],
            'last_name' => '',
            'email' => @$additions['email'],
            'address' => @$user['data']['location'],
            'email_verified_at' => now(),
            'platform' => EnumChannel::BITBUCKET,
            'avatar' => @$user['data']['links']['avatar']['href'],
            'password' => Hash::make(123456789),
            'status' => true,
            'access_token' => @$token['data']['access_token'],
            'refresh_token' => @$token['data']['refresh_token'],
            'expire_token' => date("Y-m-d H:i:s", time() + @$token['data']['expires_in'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * handle logic service api additions
     * @param array $payload
     * @param ...$variable
     * @return array
     */
    public function handleAdditional(array $payload, ...$variable)
    {
        [$token, $user] = $variable;
        $emails = $this->platform->setToken($token['data']['access_token'])->email();
        $data = [];
        if ($emails['status']) {
            foreach ($emails['data']['values'] as $item) {
                if ($item['type'] === 'email' && $item['is_primary'] && $item['is_confirmed']) {
                    $data['email'] = $item['email'];
                    break;
                }
            }
        }
        return $data;
    }

    /**
     * handle before install
     * @param ...$payload
     * @return void
     */
    public function beforeInstall(...$payload)
    {
        // TODO: Implement beforeInstall() method.
    }

    /**
     * handle between api get token and get profile
     * @param ...$payload
     * @return void
     */
    public function middleInstallBothTokenAndProfile(...$payload)
    {
        // TODO: Implement middleInstallBothTokenAndProfile() method.
    }

    /**
     * handle after install
     * @param ...$payload
     * @return void
     */
    public function afterInstall(...$payload)
    {
        // TODO: Implement afterInstall() method.
    }
}