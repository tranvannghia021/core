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

    public function register(RegisterRequest $request)
    {
        return $this->appCoreService
            ->register($request->only(array_keys(config('social.custom_request.app.register'))));
    }

    public function verify(Request $request)
    {
        if ($request->input('type') === 'verify') {

            $this->appCoreService->verify($request->all());
        } else {
            $this->appCoreService->verifyForgot($request->all());
        }
        return view('socials.verify-success');
    }

    public function reSendLinkEmail(Request $request)
    {
        $type = $request->input('type', 'verify');
        return $this->appCoreService->reSendLinkEmail($request->input('email'), $type);

    }

    public function login(LoginRequest $request)
    {
        return $this->appCoreService->login($request->only([
            'email',
            'password'
        ]));
    }

    public function user(Request $request)
    {
        $user=Core::user();
        CoreHelper::removeInfoPrivateUser($user);
        return $this->Response( $user ?? [], "User Info");
    }

    public function updateUser(Request $request)
    {
        return $this->appCoreService->updateUser($request->only(array_keys(config('social.custom_request.app.register'))));
    }

    public function delete(Request $request)
    {
        return $this->appCoreService->delete(Core::user()['id']);
    }

    public function changePassword(ChangeRequest $request)
    {
        return $this->appCoreService->changePassword(Core::user()['id'], $request->input('password_old'),
            $request->input('password'));
    }

    public function reset(ResetRequest $request)
    {
        return $this->appCoreService->forgotPassword($request->input('email'));
    }

    public function refresh(Request $request)
    {
        $token = CoreHelper::createPayloadJwt(Core::user());
        unset($token['jwt']['refresh_token'],$token['jwt']['time_expire_refresh'],$token['userInfo']);
        return $this->Response($token, "Refresh Success");
    }
}
