<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppCore\RegisterRequest;
use App\Service\AppCoreService;
use Illuminate\Http\Request;

class AppController extends Controller
{
    protected $appCoreService;
    public function __construct(AppCoreService $appCoreService)
    {
        $this->appCoreService=$appCoreService;
    }

    public function register(RegisterRequest $request){

    }
}
