<?php

namespace Devtvn\Social\Service\SocialPlatform;


use Devtvn\Social\Ecommerces\RestApi\Tiktok;
use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\ACoreService;
use Devtvn\Social\Service\ICoreService;
use Devtvn\Social\Traits\Response;

class TiktokService extends ACoreService
{
    use Response;
    public function __construct()
    {
        $this->platform=Social::driver(EnumChannel::TIKTOK);
        parent::__construct();
    }

    public function getStructure(...$payload)
    {
        // TODO: Implement getStructure() method.
    }

    public function handleAdditional(array $payload, ...$variable)
    {
        // TODO: Implement handleAdditional() method.
    }
}
