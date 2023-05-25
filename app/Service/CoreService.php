<?php
namespace App\Service;
use App\Helpers\EnumChannel;
use App\Service\SocialPlatform\FacebookService;
use App\Service\SocialPlatform\GithubService;
use App\Service\SocialPlatform\GoogleService;
use App\Service\SocialPlatform\TiktokService;

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
