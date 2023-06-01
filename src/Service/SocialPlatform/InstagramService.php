<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Devtvn\Social\Service\ICoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class InstagramService implements ICoreService
{
    use Response;

    protected $instagram;

    public function __construct()
    {
        $this->instagram = Social::driver(EnumChannel::INSTAGRAM_BASIC);
    }

    public function setVariable(array $variable): ICoreService
    {
        return $this;
    }

    public function generateUrl(array $payload)
    {
        return $this->Response([
            'url' => $this->instagram->generateUrl($payload),
            'pusher' => [
                'channel' => config('social.pusher.channel'),
                'event' => config('social.pusher.event') . $payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
        $token = $this->instagram->getAccessToken($payload['code']);
        if (!$token['status']) {
            CoreHelper::pusher($payload['ip'], [
                'status' => false,
                'error' => [
                    'type' => 'account_access_denied',
                    'message' => 'Access denied!',
                ]
            ]);
            return;
        }
        if ($payload['type'] === 'auth') {
            $user = $this->instagram->setToken($token['data']['access_token'])->profile();

            if (!$user['status']) {
                CoreHelper::pusher($payload['ip'], [
                    'status' => false,
                    'error' => [
                        'type' => 'account_access_denied',
                        'message' => 'Access denied!',
                    ]
                ]);
                return;
            }
            $data = [
                'internal_id' => (string)$user['data']['id'],
                'email_verified_at' => @$user['data']['verified_email'] ?? now(),
                'first_name' => @$user['data']['name'] ?? @$user['data']['given_name'] ??  @$user['data']['username'],
                'last_name' => '',
                'email' => @$user['data']['email'],
                'avatar' => @$user['data']['picture'],
                'password' => Hash::make(123456789),
                'platform' => EnumChannel::INSTAGRAM_BASIC,
                'status' => true,
                'access_token' => @$token['data']['access_token'],
                'expire_token' => date("Y-m-d H:i:s",
                    time() + @$token['data']['expires_in'] ?? @$token['data']['expires'] ?? 0),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $result = app(UserRepository::class)->updateOrInsert([
                'internal_id' => $data['internal_id'],
                'platform' => $data['platform'],
            ], $data);
            $data['id'] = $result['id'];
            unset($data['password'], $data['access_token']);

            CoreHelper::pusher($payload['ip'], [
                'status' => true,
                'data' => CoreHelper::createPayloadJwt($data)
            ]);

        }
    }
}