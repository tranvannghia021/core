<?php
namespace Core\Social\Service;
use Core\Social\Helpers\EnumChannel;
use Core\Social\Service\SocialPlatform\FacebookService;
use Core\Social\Service\SocialPlatform\GithubService;
use Core\Social\Service\SocialPlatform\GoogleService;
use Core\Social\Service\SocialPlatform\TiktokService;

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
