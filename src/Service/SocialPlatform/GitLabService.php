<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Devtvn\Social\Service\ICoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class GitLabService implements ICoreService
{
    use Response;
    protected $gitlab;
    public function __construct()
    {
        $this->gitlab=Social::driver(EnumChannel::GITLAB);
    }

    public function setVariable(array $variable): ICoreService
    {
        return $this;
    }

    public function generateUrl(array $payload)
    {
        return $this->Response([
            'url'=>$this->gitlab->generateUrl($payload),
            'pusher'=>[
                'channel'=>config('social.pusher.channel'),
                'event'=>config('social.pusher.event').$payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
//        try {
            $token=$this->gitlab->getAccessToken($payload['code']);
            if(!$token['status']){
                CoreHelper::pusher($payload['ip'],[
                    'status'=>false,
                    'message'=>'Access denied!'
                ]);
                return;
            }
            if($payload['type'] === 'auth'){
                $user=$this->gitlab->setToken($token['data']['access_token'])->profile();
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
                    'first_name'=>@$user['data']['name'] ?? @$user['data']['username'],
                    'last_name'=>'',
                    'email'=>@$user['data']['email'],
                    'address'=>@$user['data']['location'],
                    'email_verified_at'=>now(),
                    'platform'=>EnumChannel::GITLAB,
                    'avatar'=>@$user['data']['avatar_url'],
                    'password'=>Hash::make(123456789),
                    'status'=>true,
                    'access_token'=>@$token['data']['access_token'],
                    'refresh_token'=>@$token['data']['refresh_token'],
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

                CoreHelper::pusher($payload['ip'],CoreHelper::createPayloadJwt($data));
            }
//        }catch (\Exception $exception){
//            return $this->ResponseError($exception->getMessage());
//        }
    }
}