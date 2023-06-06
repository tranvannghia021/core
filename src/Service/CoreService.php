<?php
namespace Devtvn\Social\Service;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\SocialPlatform\BitbucketService;
use Devtvn\Social\Service\SocialPlatform\DropboxService;
use Devtvn\Social\Service\SocialPlatform\FacebookService;
use Devtvn\Social\Service\SocialPlatform\GithubService;
use Devtvn\Social\Service\SocialPlatform\GitLabService;
use Devtvn\Social\Service\SocialPlatform\GoogleService;
use Devtvn\Social\Service\SocialPlatform\InstagramService;
use Devtvn\Social\Service\SocialPlatform\LinkedinService;
use Devtvn\Social\Service\SocialPlatform\MicrosoftService;
use Devtvn\Social\Service\SocialPlatform\TiktokService;
use Devtvn\Social\Service\SocialPlatform\TwitterService;

class CoreService
{
    public static function setChannel(string $channel,array $variable = []):?ACoreService
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
            case EnumChannel::TWITTER:
                $service=app(TwitterService::class);
                break;
            case EnumChannel::INSTAGRAM_BASIC:
                $service=app(InstagramService::class);
                break;
            case EnumChannel::LINKEDIN:
                $service=app(LinkedinService::class);
                break;
            case EnumChannel::BITBUCKET:
                $service=app(BitbucketService::class);
                break;
            case EnumChannel::GITLAB:
                $service=app(GitLabService::class);
                break;
            case EnumChannel::MICROSOFT:
                $service=app(MicrosoftService::class);
                break;
            case EnumChannel::DROPBOX:
                $service=app(DropboxService::class);
                break;
        }
        return $service->setVariable($variable);
    }
}
