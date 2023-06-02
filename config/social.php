<?php
/**
 * key publish create state auth social platform or verify email
 * key private create access_token
 * key private + key publish create refresh_token
 */
return [
    'app' => [
        'name' => 'in_app',
        'url_fe'=>env('URL_FE')
    ],
    'db_connection'=>env("DB_CORE_CONNECTION",'database_core'),
    'key_jwt' => [
        'time' => [
            'token' => 3600 * 24, // 1 day
            'refresh' => 86400 * 30 // 1 month
        ],
        'alg' => 'HS256',
        'publish' => [
            'key' => env('KEY_JWT','c3o&re') // token for state
        ],
        'private' => [
            'key' => env('KEY_JWT_PRIVATE','soc@ia$lco#re')
        ]
    ],
    'platform' => [
        'facebook' => [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'base_api' => env('FACEBOOK_BASE_API','https://graph.facebook.com'),
            'version' => env('FACEBOOK_VERSION','v16.0'),
            'scope' => [
                'public_profile',
                'email'
            ],
            'field'=>[
                'id',
                'name',
                'first_name',
                'last_name',
                'email',
                'birthday',
                'gender',
                'hometown',
                'location',
                'picture',
            ]
        ],
        'google' => [
            'client_id' => env('GOOGLE_CLIENT_ID','googleapis.com'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'base_api' => env('GOOGLE_BASE_API'),
            'version' => env('GOOGLE_VERSION','v2'),
            'scope' => [
                'https://www.googleapis.com/auth/userinfo.email',
                'https://www.googleapis.com/auth/user.addresses.read',
                'https://www.googleapis.com/auth/user.birthday.read',
                'https://www.googleapis.com/auth/user.emails.read',
                'https://www.googleapis.com/auth/user.gender.read',
                'https://www.googleapis.com/auth/user.organization.read',
                'https://www.googleapis.com/auth/user.phonenumbers.read',
                'https://www.googleapis.com/auth/userinfo.profile'
            ]
        ],
        'github' => [
            'app_id' => env('GITHUB_APP_ID'),
            'client_id' => env('GITHUB_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'base_api' => env('GITHUB_BASE_API','https://api.github.com'),
            'version' => env('GITHUB_VERSION','v1'),
            'scope' => [
                'user'
            ]
        ],
        'tiktok' => [
            'app_id' => env('TIKTOK_APP_ID'),
            'client_id' => env('TIKTOK_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('TIKTOK_CLIENT_SECRET'),
            'base_api' => env('TIKTOK_BASE_API','https://open.tiktokapis.com'),
            'version' => env('TIKTOK_VERSION','v2'),
            'scope' => [
                'user.info.basic',
            ],
            'field'=>[
                'open_id','union_id','avatar_url','display_name',
                'bio_description',
                'profile_deep_link',
                'is_verified',
                'follower_count',
                'following_count',
                'likes_count',
                'video_count',
            ]
        ],
        'twitter' => [
            'client_id' => env('TWITTER_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('TWITTER_CLIENT_SECRET'),
            'base_api' => env('TWITTER_BASE_API','https://api.twitter.com'),
            'version' => env('TWITTER_VERSION','2'),
            'scope' => [
               'users.read','tweet.read'
            ],
            'field'=>[

            ]
        ],
        'instagram_basic' => [
            'client_id' => env('INSTAGRAM_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
            'base_api' => env('INSTAGRAM_BASE_API','https://graph.instagram.com'),
            'scope' => [
               'email', 'public_profile'
            ],
            'field'=>[
                'id','username'
            ]
        ],
        'linkedin' => [
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
            'base_api' => env('LINKEDIN_BASE_API','https://api.linkedin.com'),
            'version' => env('LINKEDIN_VERSION','v2'),
            'scope' => [
                'r_liteprofile',
                'r_emailaddress',
            ],

        ],
        'bitbucket' => [
            'client_id' => env('BITBUCKET_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('BITBUCKET_CLIENT_SECRET'),
            'base_api' => env('BITBUCKET_BASE_API','https://api.bitbucket.org'),
            'version' => env('BITBUCKET_VERSION','2.0'),
            'scope' => [
                'email',
            ],

        ],
        'gitlab' => [
            'app_id' => env('GITLAB_APP_ID'),
            'client_id' => env('GITLAB_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/handle/auth',
            'client_secret' => env('GITLAB_CLIENT_SECRET'),
            'base_api' => env('GITLAB_BASE_API','https://gitlab.com'),
            'version' => env('GITLAB_VERSION','v4'),
            'scope' => [
                'read_user'
            ],

        ],
    ],
    'pusher' => [
        'channel' => env('CHANNEL_NAME', 'core'),
        'event' => env('EVENT_NAME', 'event_'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ],
    ],
    'custom_request'=>[
        'app'=>[
            'register'=>[
                'email'=>'required|email|string',
                'password'=>'required|min:6|max:20',
                'confirm'=>'required_with:password|same:password|min:6|max:20',
                'first_name'=>'required',
                'last_name'=>'required',
                'avatar'=>'required',
                'gender'=>'required',
                'phone'=>'required|string',
                'birthday'=>'required|before:yesterday|date|date_format:Y-m-d',
                'address'=>'nullable',
                'refresh_token'=>'nullable',
                'access_token'=>'nullable',
                'expire_token'=>'nullable',
                'settings'=>'nullable',
                'other'=>'nullable',
            ]
        ]
    ],
    'storage'=>[
        'disk'=>'public',
        'image_ext'=>['png', 'jpg', 'jpeg', 'gif']
    ],
    'queue'=>[
        'mail'=>[
            'on_queue'=>'verify-email',
            'on_connection'=>'redis'
        ]
    ]

];
