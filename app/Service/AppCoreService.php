<?php

namespace App\Service;

use App\Helpers\CoreHelper;
use App\Jobs\VerifyEmailJob;
use App\Repositories\UserRepository;
use App\Service\Contracts\UserContract;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;


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
            throw new Exception(__('passwords.user'));
        }
        if(!$user['status']){
            throw new Exception(__('validation.verify_email'));
        }
        if(!Hash::check($payload['password'],$user['password'])){
            throw new Exception(__('passwords.incorrect'));
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
            if(!empty($result)) return $this->ResponseError(__('auth.register.exists'));
            $payload['password'] = Hash::make($payload['confirm']);
            if (isset($payload['avatar'])) {
                $avatar = CoreHelper::saveImgBase64('app', $payload['avatar']);
                if ($avatar === false) {
                    return $this->ResponseError(__('validation.image_64', [
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
            return $this->Response($result,__('auth.register.success'));
        }catch (Exception $exception){
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
            throw new Exception(__('passwords.user'));
        }
    }

    public function reSendLinkEmail(string $email){
        $result=$this->userRepository->findBy([
            'email'=>$email,
            'platform'=>config('social.app.name'),
            'status'=>false
        ],['id','email']);
        if(empty($result)){
            throw new Exception(__('passwords.user'));
        }
        VerifyEmailJob::dispatch($result)
            ->onConnection(config('social.queue.mail.on_connection'))
            ->onQueue(config('social.queue.mail.on_queue'));
        return $this->Response([],__('passwords.re_sent'));
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
            throw new Exception(__('passwords.user'));
        }
        $user->delete();
        return $this->Response([],__('auth.delete.success'));
    }

    public function check()
    {
        return !is_null($this->user);
    }
}
