<?php
namespace Devtvn\Social\Service;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\SocialPlatform\FacebookService;
use Devtvn\Social\Service\SocialPlatform\GithubService;
use Devtvn\Social\Service\SocialPlatform\GoogleService;
use Devtvn\Social\Service\SocialPlatform\TiktokService;

class CoreService
{
    public static function setChannel(string $channel,array $variable = []):?ICoreService
    {
        $service=null;
        switch ($channel){
            case EnumChannel::FACEBOOK:
                $service=app(FacebookService::class);
                break;
            case EnumChannel::TIKTOK:
                $service=app(TiktokService::class);
                break;
            case EnumChannel::GOOGLE:
                $service=app(GoogleService::class);
                break;
            case EnumChannel::GITHUB:
                $service=app(GithubService::class);
                break;
        }
        return $service->setVariable($variable);
    }
}
