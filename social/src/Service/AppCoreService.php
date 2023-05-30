<?php

namespace Core\Social\Service;

use Core\Social\Facades\Core;
use Core\Social\Helpers\CoreHelper;
use Core\Social\Jobs\VerifyEmailJob;
use Core\Social\Repositories\UserRepository;
use Core\Social\Service\Contracts\UserContract;
use Core\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;


class AppCoreService implements UserContract
{

    use Response;
    protected $userRepository,$user;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository=$userRepository;
    }
    public function login(array $payload)
    {
        $user=$this->userRepository->findBy([
            'email'=>$payload['email'],
            'platform'=>config('social.app.name')
        ]);
        if(empty($user)){
            throw new \Exception(__('core.user'));
        }
        if(!$user['status']){
            throw new \Exception(__('core.verify_email'));
        }
        if(!Hash::check($payload['password'],$user['password'])){
            throw new \Exception(__('core.incorrect'));
        }
        unset($user['password'],$user['refresh_token'],$user['access_token']);
        return $this->Response(CoreHelper::createPayloadJwt($user),"Login success");
    }

    public function register(array $payload)
    {
        try {
            $result=$this->userRepository->findBy([
                'email'=>$payload['email'],
                'platform'=>config('social.app.name')
            ]);
            if(!empty($result)) return $this->ResponseError(__('core.register.exists'));
            $payload['password'] = Hash::make($payload['confirm']);
            if (isset($payload['avatar'])) {
                $avatar = CoreHelper::saveImgBase64('app', $payload['avatar']);
                if ($avatar === false) {
                    return $this->ResponseError(__('core.image_64', [
                        'attribute' => 'avatar'
                    ]));
                }
                $payload['avatar'] = $avatar;
            }
            unset($payload['confirm'],$payload['email_verified_at'],$payload['status'],$payload['is_disconnect']);
            $result=$this->userRepository->create($payload)->toArray();
            VerifyEmailJob::dispatch([
                'id'=>$result['id'],
                'email'=>$result['email']
            ])
                ->onConnection(config('social.queue.mail.on_connection'))
                ->onQueue(config('social.queue.mail.on_queue'));
            unset($result['password']);
            return $this->Response($result,__('core.register.success'));
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function verify(array $payload){
        $result=$this->userRepository->find($payload['id']);
        if(!empty($result)){
            $result->update([
                'status'=>true,
                'is_disconnect'=>false,
                'email_verified_at'=>now(),
            ]);
        }else{
            throw new \Exception(__('core.user'));
        }
    }

    public function reSendLinkEmail(string $email,string $type){
        $result=$this->userRepository->findBy([
            'email'=>$email,
            'platform'=>config('social.app.name'),
            'status'=>$type !== 'verify'
        ],['id','email']);
        if(empty($result)){
            throw new \Exception(__('core.user'));
        }
        VerifyEmailJob::dispatch($result,$type)
            ->onConnection(config('social.queue.mail.on_connection'))
            ->onQueue(config('social.queue.mail.on_queue'));
        return $this->Response([],__('core.re_sent'));
    }


    public function setUser(array $user) :UserContract
    {
        $this->user=$user;
        return $this;
    }

    public function user()
    {
       return $this->user;
    }

    public function delete(int $id)
    {
        $user=$this->userRepository->find($id);
        if(empty($user)){
            throw new \Exception(__('core.user'));
        }
        $user->delete();
        return $this->Response([],__('core.delete.success'));
    }

    public function check()
    {
        return !is_null($this->user);
    }

    public function updateUser(array $payload)
    {
        unset($payload['password'],$payload['internal_id'],
                $payload['platform'],$payload['email_verified_at'],$payload['refresh_token'],
                $payload['access_token'],$payload['expire_token'],$payload['is_disconnect']);
        if(isset($payload['avatar'])){
                $avatar=CoreHelper::saveImgBase64('app',$payload['avatar']);
                if($avatar === false) throw new \Exception(__('core.image_64'));
                $payload['avatar']=$avatar;
        }
       $this->userRepository->update(Core::user()['id'],$payload);
       return $this->Response([],__('core.update.success'));
    }

    public function changePassword(int $id, string $passwordOld,string $password)
    {
       $user=$this->userRepository->find($id);
       if(empty($user)){
           throw new \Exception(__('core.user'));
       }
       if(!Hash::check($passwordOld,$user['password'])){
           throw new \Exception(__('core.password'));
       }
       $user->update([
           'password'=>Hash::make($password),
       ]);
       return $this->Response([],__('core.reset'));

    }

    public function forgotPassword(string $email)
    {
        $user=$this->userRepository->findBy([
            'email'=>$email,
            'status'=>true,
            'is_disconnect'=>false,
            'platform'=>config('social.app.name')
        ],[
            'id','email'
        ]);
        if(empty($user)){
            throw new \Exception(__('core.user'));
        }
        VerifyEmailJob::dispatch($user,'forgot')
            ->onConnection(config('social.queue.mail.on_connection'))
            ->onQueue(config('social.queue.mail.on_queue'));
        return $this->Response([],__('core.re_sent'));
    }

    public function verifyForgot(array $payload)
    {
       $user=$this->userRepository->find($payload['id']);
       if(empty($user)){
           throw new \Exception(__('core.user'));
       }
       CoreHelper::pusher('forgot_'.$user['email'],[
           'id'=>$user['id'],
           'email'=>$user['email']
       ]);
    }
}
