<?php

namespace App\Http\Controllers;

use App\Facades\Core;
use App\Helpers\CoreHelper;
use App\Http\Requests\AppCore\ChangeRequest;
use App\Http\Requests\AppCore\LoginRequest;
use App\Http\Requests\AppCore\RegisterRequest;
use App\Http\Requests\AppCore\ResetRequest;
use App\Service\Contracts\UserContract;
use App\Traits\Response;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use Response;

    protected $appCoreService;

    public function __construct(UserContract $appCoreService)
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
        return view('verify-success');
    }

    public function reSendLinkEmail(RegisterRequest $request)
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
        return $this->Response(Core::user() ?? [], "User Info");
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
