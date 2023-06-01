<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Devtvn\Social\Service\ICoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class GoogleService implements ICoreService
{
    use Response;
    protected $google,$userRepository;
    public function __construct()
    {
        $this->google=Social::driver(EnumChannel::GOOGLE);
        $this->userRepository=app(UserRepository::class);
    }

    public function setVariable(array $variable): ICoreService
    {
       return $this;
    }

    public function generateUrl(array $payload)
    {
        return $this->Response([
            'url'=>$this->google->generateUrl($payload),
            'pusher'=>[
                'channel'=>config('social.pusher.channel'),
                'event'=>config('social.pusher.event').$payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
        $token=$this->google->getAccessToken($payload['code']);
        if(!$token['status']){
            CoreHelper::pusher($payload['ip'],[
                'status'=>false,
                'error'=>[
                    'type'=>'account_access_denied',
                    'message'=>'Access denied!',
                ]
            ]);
            return;
        }
        if($payload['type'] === 'auth'){
            $user=$this->google->setToken($token['data']['access_token'])->profile();
            if(!$user['status']){
                CoreHelper::pusher($payload['ip'],[
                    'status'=>false,
                    'error'=>[
                        'type'=>'account_access_denied',
                        'message'=>'Access denied!',
                    ]
                ]);
                return;
            }

            $data=[
                'internal_id'=>(string)$user['data']['id'],
                'email_verified_at'=>$user['data']['verified_email'] ? now() : null,
                'first_name'=>@$user['data']['name'] ?? $user['data']['given_name'],
                'last_name'=>'',
                'email'=>$user['data']['email'],
                'avatar'=>$user['data']['picture'],
                'password'=>Hash::make(123456789),
                'platform'=>EnumChannel::GOOGLE,
                'status'=>true,
                'access_token'=>@$token['data']['access_token'],
                'expire_token'=>date("Y-m-d H:i:s",time() + @$token['data']['expires_in'] ?? 0),
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
            $result=app(UserRepository::class)->updateOrInsert([
                'internal_id'=>$data['internal_id'],
                'email'=>$data['email'],
                'platform'=>$data['platform'],
            ],$data);
            $data['id']=$result['id'];
            unset($data['password'],$data['access_token']);

            CoreHelper::pusher($payload['ip'],[
                'status'=>true,
                'data'=>CoreHelper::createPayloadJwt($data)
            ]);
        }
    }
}
