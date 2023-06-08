<?php

namespace Devtvn\Social\Http\Controllers;

use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Helpers\EnumChannel;
use Devtvn\Social\Service\CoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mockery\Exception;

class CoreController extends Controller
{
    public function generateUrl(Request $request,$platform){
        if(!in_array($platform,EnumChannel::PLATFROM)){
            throw new Exception(__('core.platform_not_support'));
        }
        $payload=$request->all();
        $payload['platform']=$platform;
        CoreHelper::setIpState($payload);
        return CoreService::setChannel($platform)->generateUrl($payload);
    }

    public function handleAuth(Request $request){
        if(CoreHelper::handleErrorSocial($request)){
            return ['status'=>false,'error'=>'Auth failed'];
        }
        CoreService::setChannel($request->input('platform'))->auth($request->all());
        return Redirect::to(config('social.app.url_fe'));
    }
}
