<?php

namespace Devtvn\Social\Service\SocialPlatform;

use Devtvn\Social\Facades\Social;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Repositories\UserRepository;
use Devtvn\Social\Service\ICoreService;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class BitbucketService implements ICoreService
{
    use Response;
    protected $bitbucket;
    public function __construct()
    {
        $this->bitbucket=Social::driver(EnumChannel::BITBUCKET);
    }

    public function setVariable(array $variable): ICoreService
    {
        return $this;
    }

    public function generateUrl(array $payload)
    {
        return $this->Response([
            'url'=>$this->bitbucket->generateUrl($payload),
            'pusher'=>[
                'channel'=>config('social.pusher.channel'),
                'event'=>config('social.pusher.event').$payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
        try {
            $token=$this->bitbucket->getAccessToken($payload['code']);
            if(!$token['status']){
                CoreHelper::pusher($payload['ip'],[
                    'status'=>false,
                    'message'=>'Access denied!'
                ]);
                return;
            }
            if($payload['type'] === 'auth'){
                $user=$this->bitbucket->setToken($token['data']['access_token'])->profile();
                $emails=$this->bitbucket->setToken($token['data']['access_token'])->email();
                $email=null;
               if($emails['status']){
                   foreach ($emails['data']['values'] as $item) {
                       if ($item['type'] === 'email' && $item['is_primary'] && $item['is_confirmed']) {
                           $email=$item['email'];
                           break;
                       }
                   }
               }
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
                    'internal_id'=>(string)$user['data']['account_id'],
                    'first_name'=>@$user['data']['display_name'] ?? @$user['data']['username'],
                    'last_name'=>'',
                    'email'=>$email,
                    'address'=>@$user['data']['location'],
                    'email_verified_at'=>now(),
                    'platform'=>EnumChannel::BITBUCKET,
                    'avatar'=>@$user['data']['links']['avatar']['href'],
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
        }catch (\Exception $exception){
            return $this->ResponseError($exception->getMessage());
        }
    }
}