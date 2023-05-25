<?php

namespace App\Http\Controllers;

use App\Facades\Core;
use App\Http\Requests\AppCore\LoginRequest;
use App\Http\Requests\AppCore\RegisterRequest;
use App\Service\Contracts\UserContract;
use App\Traits\Response;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use Response;
    protected $appCoreService;
    public function __construct(UserContract $appCoreService)
    {
        $this->appCoreService=$appCoreService;
    }

    public function register(RegisterRequest $request){
        return $this->appCoreService
            ->register($request->only(array_keys(config('social.custom_request.app.register'))));
    }

    public function verify(Request $request){
        $this->appCoreService->verify($request->all());
        return view('verify-success');
    }

    public function reSendLinkEmail(RegisterRequest $request){
        return $this->appCoreService->reSendLinkEmail($request->input('email'));
    }

    public function login(LoginRequest $request){
        return $this->appCoreService->login($request->only([
            'email',
            'password'
        ]));
    }

    public function user(Request $request){
        dd(Core::user());
        return $this->Response(Core::user() ?? [],"User Info");
    }

    public function delete(Request $request){
        return $this->appCoreService->delete(Core::user()['id']);
    }
}
