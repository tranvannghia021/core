<?php

namespace Devtvn\Social\Service;

use Devtvn\Social\Facades\Core;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Jobs\VerifyEmailJob;
use Devtvn\Social\Repositories\CoreRepository;
use Devtvn\Social\Service\Contracts\CoreContract;
use Devtvn\Social\Traits\Response;
use Illuminate\Support\Facades\Hash;


class AppCoreService implements CoreContract
{

    use Response;

    /**
     * @var CoreRepository
     */
    protected $coreRepository;

    /**
     * @var mixed
     */
    protected $user;


    public function __construct(CoreRepository $coreRepository)
    {
        $this->coreRepository = $coreRepository;
    }

    /**
     * login app
     * @param array $payload
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function login(array $payload)
    {
        $user = $this->coreRepository->findBy([
            'email' => $payload['email'],
            'platform' => config('social.app.name')
        ]);
        if (empty($user)) {
            throw new \Exception(__('core.user'));
        }
        if (!$user['status']) {
            throw new \Exception(__('core.verify_email'));
        }
        if (!Hash::check($payload['password'], $user['password'])) {
            throw new \Exception(__('core.incorrect'));
        }
        unset($user['password'], $user['refresh_token'], $user['access_token']);
        return $this->Response(CoreHelper::createPayloadJwt($user), "Login success");
    }

    /**
     * regiser a user in app
     * @param array $payload
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function register(array $payload)
    {
        try {
            $result = $this->coreRepository->findBy([
                'email' => $payload['email'],
                'platform' => config('social.app.name')
            ]);
            if (!empty($result)) {
                return $this->ResponseError(__('core.register.exists'));
            }
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
            unset($payload['confirm'], $payload['email_verified_at'], $payload['status'], $payload['is_disconnect']);
            $result = $this->coreRepository->create($payload)->toArray();
            VerifyEmailJob::dispatch([
                'id' => $result['id'],
                'email' => $result['email']
            ])
                ->onConnection(config('social.queue.mail.on_connection'))
                ->onQueue(config('social.queue.mail.on_queue'));
            unset($result['password']);
            return $this->Response($result, __('core.register.success'));
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * verify callback
     * @param array $payload
     * @return mixed|void
     * @throws \Exception
     */
    public function verify(array $payload)
    {
        $result = $this->coreRepository->find($payload['id']);
        if (!empty($result)) {
            $result->update([
                'status' => true,
                'is_disconnect' => false,
                'email_verified_at' => now(),
            ]);
        } else {
            throw new \Exception(__('core.user'));
        }
    }

    /**
     * re send url verify to email
     * @param string $email
     * @param string $type
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function reSendLinkEmail(string $email, string $type)
    {
        $result = $this->coreRepository->findBy([
            'email' => $email,
            'platform' => config('social.app.name'),
            'status' => $type !== 'verify'
        ], ['id', 'email']);
        if (empty($result)) {
            throw new \Exception(__('core.user'));
        }
        VerifyEmailJob::dispatch($result, $type)
            ->onConnection(config('social.queue.mail.on_connection'))
            ->onQueue(config('social.queue.mail.on_queue'));
        return $this->Response([], __('core.re_sent'));
    }


    /**
     * set user global
     * @param array $user
     * @return CoreContract
     */
    public function setUser(array $user): CoreContract
    {
        $this->user = $user;
        return $this;
    }

    /**
     * get user info
     * @return mixed
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * delete user
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $user = $this->coreRepository->find($id);
        if (empty($user)) {
            throw new \Exception(__('core.user'));
        }
        $user->delete();
        return $this->Response([], __('core.delete.success'));
    }

    /**
     * check variable user has supper-global
     * @return bool
     */
    public function check()
    {
        return !is_null($this->user);
    }

    /**
     * update user
     * @param array $payload
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function updateUser(array $payload)
    {
        unset($payload['password'], $payload['internal_id'],
            $payload['platform'], $payload['email_verified_at'], $payload['refresh_token'],
            $payload['access_token'], $payload['expire_token'], $payload['is_disconnect']);
        if (isset($payload['avatar'])) {
            $avatar = CoreHelper::saveImgBase64('app', $payload['avatar']);
            if ($avatar === false) {
                throw new \Exception(__('core.image_64'));
            }
            $payload['avatar'] = $avatar;
        }
        $this->coreRepository->update(Core::user()['id'], $payload);
        return $this->Response([], __('core.update.success'));
    }

    /**
     * change password
     * @param int $id
     * @param string $passwordOld
     * @param string $password
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function changePassword(int $id, string $passwordOld, string $password)
    {
        $user = $this->coreRepository->find($id);
        if (empty($user)) {
            throw new \Exception(__('core.user'));
        }
        if (!Hash::check($passwordOld, $user['password'])) {
            throw new \Exception(__('core.password'));
        }
        $user->update([
            'password' => Hash::make($password),
        ]);
        return $this->Response([], __('core.reset'));

    }

    /**
     * forgot password
     * @param string $email
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function forgotPassword(string $email)
    {
        $user = $this->coreRepository->findBy([
            'email' => $email,
            'status' => true,
            'is_disconnect' => false,
            'platform' => config('social.app.name')
        ], [
            'id',
            'email'
        ]);
        if (empty($user)) {
            throw new \Exception(__('core.user'));
        }
        VerifyEmailJob::dispatch($user, 'forgot')
            ->onConnection(config('social.queue.mail.on_connection'))
            ->onQueue(config('social.queue.mail.on_queue'));
        return $this->Response([], __('core.re_sent'));
    }

    /**
     * verify callback forgot
     * @param array $payload
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pusher\ApiErrorException
     * @throws \Pusher\PusherException
     */
    public function verifyForgot(array $payload)
    {
        $user = $this->coreRepository->find($payload['id']);
        if (empty($user)) {
            throw new \Exception(__('core.user'));
        }
        CoreHelper::pusher('forgot_' . $user['email'], [
            'id' => $user['id'],
            'email' => $user['email']
        ]);
    }
}
