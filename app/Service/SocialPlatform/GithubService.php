<?php

namespace App\Service\SocialPlatform;

use App\Ecommerces\RestApi\Github\Github;
use App\Helpers\CoreHelper;
use App\Helpers\EnumChannel;
use App\Repositories\UserRepository;
use App\Service\ICoreService;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

class GithubService implements ICoreService
{
    use Response;
    protected $github,$userRepository;
    public function __construct(Github $github)
    {
        $this->github=$github;
        $this->userRepository=app(UserRepository::class);
    }

    public function setVariable(array $variable):ICoreService
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
            'url'=>$this->github->generateUrl($payload),
            'pusher'=>[
                'channel'=>config('social.pusher.channel'),
                'event'=>config('social.pusher.event').$payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
        $token=$this->github->getToken($payload['code']);
        if(!$token['status']){
            CoreHelper::pusher($payload['ip'],[
                'status'=>false,
                'error'=>[
                    'type'=>'account_access_denied',
                    'message'=>'Access denied!',
                    'platform'=>'github'
                ]
            ]);
            return;
        }
        if($payload['type'] == 'auth') {
            $user = $this->github->setToken($token['data']['access_token'])->getProfile();
            if (!$user['status']) {
                CoreHelper::pusher($payload['ip'], [
                    'status' => false,
                    'error' => [
                        'type' => 'account_access_denied',
                        'message' => 'Access denied!',
                        'platform' => 'github'
                    ]
                ]);
                return;
            }

            $data = [
                'internal_id' => (string)$user['data']['id'],
                'first_name' => $user['data']['name'],
                'last_name' => @$user['data']['last_name'] ?? '',
                'avatar' => $user['data']['avatar_url'],
                'email' => @$user['data']['email'] ?? $user['data']['login'] . '@gmail.com',
                'email_verified_at' => now(),
                'platform' => EnumChannel::GITHUB,
                'password' => Hash::make(123456789),
                'status' => true,
                'access_token'=>@$token['data']['access_token'],
                'refresh_token'=>@$token['data']['refresh_token'],
                'expire_token'=>date("Y-m-d H:i:s",time() + @$token['data']['expires_in'] ?? 0),
                'address'=>@$user['data']['location'],
                'created_at'=>now(),
                'updated_at'=>now(),
            ];


            $result = $this->userRepository->updateOrInsert([
                'internal_id' => $data['internal_id'],
                'email' => $data['email'],
                'platform' => $data['platform'],
            ], $data);
            unset($data['password'],$data['access_token'],$data['refresh_token']);
            $data['id'] = $result['id'];

            CoreHelper::pusher($payload['ip'], CoreHelper::createPayloadJwt($data));
        }
    }
}
