<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\ACoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class ShopifyService extends ACoreService
{
    use Response;
    public function __construct()
    {
        $this->platform=Social::driver(EnumChannel::SHOPIFY);
        parent::__construct();
    }

    public function getStructure(...$payload)
    {
        [$token,$user]=$payload;
        $user=$user['data']['shop'];
        return [
            'internal_id' => (string)$user['id'],
            'email_verified_at' =>now(),
            'first_name' => @$user['shop_owner'],
            'email' => $user['email'],
            'password' => Hash::make(123456789),
            'platform' => EnumChannel::SHOPIFY,
            'raw_domain'=>$user['myshopify_domain'],
            'domain'=>$user['domain'],
            'status' => true,
            'address'=>$user['address1'] ?? $user['address2'] ?? $user['country_name'],
            'access_token' => @$token['data']['access_token'],
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