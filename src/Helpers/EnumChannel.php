<?php

namespace Devtvn\Social\Helpers;

class EnumChannel
{
    const FACEBOOK = 'facebook';
    const GOOGLE = 'google';
    const GITHUB = 'github';
    const TIKTOK = 'tiktok';
    const TWITTER = 'twitter';
    const INSTAGRAM_BASIC = 'instagram';
    const LINKEDIN = 'linkedin';
    const BITBUCKET = 'bitbucket';
    const GITLAB = 'gitlab';
    const MICROSOFT = 'microsoft';
    const DROPBOX = 'dropbox';
    const REDDIT = 'reddit';
    const PINTEREST = 'pinterest';
    const LINE = 'line';
    const SHOPIFY = 'shopify';
    const PLATFROM = [
        self::FACEBOOK,
        self::GITHUB,
        self::TIKTOK,// fix
        self::GOOGLE,
        self::TWITTER,
        self::INSTAGRAM_BASIC,
        self::LINKEDIN,
        self::BITBUCKET,
        self::GITLAB,
        self::MICROSOFT,
        self::DROPBOX,
        self::REDDIT,
        self::PINTEREST,
        self::LINE,
        self::SHOPIFY,
    ];
}
