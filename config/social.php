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
    'key_jwt' => [
        'time' => [
            'token' => 3600 * 24, // 1 day
            'refresh' => 86400 * 30 // 1 month
        ],
        'alg' => 'HS256',
        'publish' => [
            'key' => env('KEY_JWT') // token for state
        ],
        'private' => [
            'key' => env('KEY_JWT_PRIVATE')
        ]
    ],
    'platform' => [
        'facebook' => [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/facebook/handle/auth',
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'base_api' => env('FACEBOOK_BASE_API'),
            'version' => env('FACEBOOK_VERSION'),
            'scope' => [
                'public_profile',
                'email'
            ]
        ],
        'google' => [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/google/handle/auth',
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'base_api' => env('GOOGLE_BASE_API'),
            'version' => env('GOOGLE_VERSION'),
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
            'host' => env('GITHUB_HOST'),
            'client_id' => env('GITHUB_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/api/github/handle/auth',
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'base_api' => env('GITHUB_BASE_API'),
            'version' => env('GITHUB_VERSION'),
            'scope' => [
                'user'
            ]
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
