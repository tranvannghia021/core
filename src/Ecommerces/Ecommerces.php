<?php

namespace Devtvn\Social\Ecommerces;

use Devtvn\Social\Ecommerces\RestApi\Bitbucket;
use Devtvn\Social\Ecommerces\RestApi\Dropbox;
use Devtvn\Social\Ecommerces\RestApi\Facebook;
use Devtvn\Social\Ecommerces\RestApi\Github;
use Devtvn\Social\Ecommerces\RestApi\Gitlab;
use Devtvn\Social\Ecommerces\RestApi\Google;
use Devtvn\Social\Ecommerces\RestApi\Instagram;
use Devtvn\Social\Ecommerces\RestApi\Linkedin;
use Devtvn\Social\Ecommerces\RestApi\Microsoft;
use Devtvn\Social\Ecommerces\RestApi\Tiktok;
use Devtvn\Social\Ecommerces\RestApi\Twitter;
use Devtvn\Social\Helpers\EnumChannel;

class Ecommerces
{
    public static function driver($channel): ?AEcommerce
    {
        $ec = null;
        switch ($channel) {
            case EnumChannel::GOOGLE;
                $ec = app(Google::class);
                break;
            case EnumChannel::GITHUB;
                $ec = app(Github::class);
                break;
            case EnumChannel::FACEBOOK;
                $ec = app(Facebook::class);
                break;
            case EnumChannel::TIKTOK;
                $ec = app(Tiktok::class);
                break;
            case EnumChannel::TWITTER;
                $ec = app(Twitter::class);
                break;
            case EnumChannel::INSTAGRAM_BASIC;
                $ec = app(Instagram::class);
                break;
            case EnumChannel::LINKEDIN;
                $ec = app(Linkedin::class);
                break;
            case EnumChannel::BITBUCKET;
                $ec = app(Bitbucket::class);
                break;
            case EnumChannel::GITLAB;
                $ec = app(Gitlab::class);
                break;
            case EnumChannel::MICROSOFT;
                $ec = app(Microsoft::class);
                break;
            case EnumChannel::DROPBOX;
                $ec = app(Dropbox::class);
                break;
            default:

        }
        return $ec;
    }
}