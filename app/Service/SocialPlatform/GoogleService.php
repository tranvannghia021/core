<?php

namespace App\Service\SocialPlatform;

use App\Ecommerces\RestApi\Google\Google;
use App\Helpers\CoreHelper;
use App\Helpers\EnumChannel;
use App\Repositories\UserRepository;
use App\Service\ICoreService;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

class GoogleService implements ICoreService
{
    use Response;
    protected $google,$userRepository;
    public function __construct(Google $google)
    {
        $this->google=$google;
        $this->userRepository=app(UserRepository::class);
    }

    public function setVariable(array $variable): ICoreService
    {
       return $this;
    }

    public function generateUrl(array $payload)
    {
        $result=CoreHelper::ip();
        $payload['ip']=request()->ip();
        if($result['status']){
            $payload['ip']=$result['ip'];
        }
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
        $token=$this->google->getToken($payload['code']);
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
            $user=$this->google->setToken($token['data']['access_token'])->getProfile();
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
