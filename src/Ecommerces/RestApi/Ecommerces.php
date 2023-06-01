<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use Devtvn\Social\Helpers\EnumChannel;

class Ecommerces
{
    public static function driver($channel): ?IEcommerce
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
            default:

        }
        return $ec;
    }
}