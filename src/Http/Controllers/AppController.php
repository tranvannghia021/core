<?php

namespace Devtvn\Social\Http\Controllers;

use Devtvn\Social\Facades\Core;
use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Http\Requests\AppCore\ChangeRequest;
use Devtvn\Social\Http\Requests\AppCore\LoginRequest;
use Devtvn\Social\Http\Requests\AppCore\RegisterRequest;
use Devtvn\Social\Http\Requests\AppCore\ResetRequest;
use Devtvn\Social\Service\Contracts\CoreContract;
use Devtvn\Social\Traits\Response;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use Response;

    protected $appCoreService;

    public function __construct(CoreContract $appCoreService)
    {
        $this->appCoreService = $appCoreService;
    }

    /**
     * register a user in app
     * @param RegisterRequest $request
     * @return mixed
     */
    public function register(RegisterRequest $request)
    {
        return $this->appCoreService
            ->register($request->only(array_keys(config('social.custom_request.app.register'))));
    }

    /**
     * verify callback
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function verify(Request $request)
    {
        if ($request->input('type') === 'verify') {

            $this->appCoreService->verify($request->all());
        } else {
            $this->appCoreService->verifyForgot($request->all());
        }
        return view('socials.verify-success');
    }

    /**
     * send link to email
     * @param Request $request
     * @return mixed
     */
    public function reSendLinkEmail(Request $request)
    {
        $type = $request->input('type', 'verify');
        return $this->appCoreService->reSendLinkEmail($request->input('email'), $type);

    }

    /**
     * login
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        return $this->appCoreService->login($request->only([
            'email',
            'password'
        ]));
    }

    /**
     * get info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = Core::user();
        CoreHelper::removeInfoPrivateUser($user);
        return $this->Response($user ?? [], "User Info");
    }

    /**
     * update user
     * @param Request $request
     * @return mixed
     */
    public function updateUser(Request $request)
    {
        return $this->appCoreService->updateUser($request->only(array_keys(config('social.custom_request.app.register'))));
    }

    /**
     * delete user
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        return $this->appCoreService->delete(Core::user()['id']);
    }

    /**
     * change password
     * @param ChangeRequest $request
     * @return mixed
     */
    public function changePassword(ChangeRequest $request)
    {
        return $this->appCoreService->changePassword(Core::user()['id'], $request->input('password_old'),
            $request->input('password'));
    }

    /**
     * forgot password
     * @param ResetRequest $request
     * @return mixed
     */
    public function reset(ResetRequest $request)
    {
        return $this->appCoreService->forgotPassword($request->input('email'));
    }

    /**
     * refresh token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $token = CoreHelper::createPayloadJwt(Core::user());
        unset($token['jwt']['refresh_token'], $token['jwt']['time_expire_refresh'], $token['userInfo']);
        return $this->Response($token, "Refresh Success");
    }
}
