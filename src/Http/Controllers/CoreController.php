<?php

namespace Devtvn\Social\Http\Controllers;

use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Service\CoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CoreController extends Controller
{
    public function generateUrl(Request $request,$platform){
        return CoreService::setChannel($platform)->generateUrl($request->all());
    }

    public function handleAuth(Request $request,$platform){
        if(CoreHelper::handleErrorSocial($request)){
            return ['status'=>false,'error'=>'Auth failed'];
        }
        CoreService::setChannel($platform)->auth($request->all());
        return Redirect::to(config('social.app.url_fe'));
    }
}
