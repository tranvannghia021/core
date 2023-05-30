<?php
namespace Core\Social\Service\SocialPlatform;
use Core\Social\Ecommerces\RestApi\Facebook\Facebook;
use Core\Social\Helpers\CoreHelper;
use Core\Social\Helpers\EnumChannel;
use Core\Social\Repositories\UserRepository;
use Core\Social\Service\ICoreService;
use Core\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;

class FacebookService implements ICoreService
{
    use Response;
    protected $facebookApi,$userRepository;
    public function __construct(Facebook $facebookApi)
    {
        $this->facebookApi=$facebookApi;
        $this->userRepository=app(UserRepository::class);
    }

    public function setVariable(array $variable) :ICoreService
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
            'url'=>$this->facebookApi->generateUrl($payload),
            'pusher'=>[
                'channel'=>config('social.pusher.channel'),
                'event'=>config('social.pusher.event').$payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
        try {
            $token=$this->facebookApi->getToken($payload['code']);
            if(!$token['status']){
                CoreHelper::pusher($payload['ip'],[
                    'status'=>false,
                    'message'=>'Access denied!'
                ]);
                return;
            }
            if($payload['type'] == 'auth'){
                $user=$this->facebookApi->setToken($token['data']['access_token'])->getProfile();
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
                    'first_name'=>$user['data']['first_name'],
                    'last_name'=>$user['data']['last_name'],
                    'email'=>$user['data']['email'],
                    'email_verified_at'=>now(),
                    'platform'=>EnumChannel::FACEBOOK,
                    'avatar'=>@$user['data']['picture']['data']['url'],
                    'password'=>Hash::make(123456789),
                    'status'=>true,
                    'access_token'=>@$token['data']['access_token'],
                    'expire_token'=>date("Y-m-d H:i:s",time() + @$token['data']['expires_in'] ?? 0),
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];

                $result=$this->userRepository->updateOrInsert([
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
