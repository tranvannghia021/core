<?php

namespace Devtvn\Social\Http\Controllers;

use Devtvn\Social\Facades\Core;
use Devtvn\Social\Helpers\CoreHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class DebugController extends Controller
{
    public function test(Request $request){

        CoreHelper::pusher('forgot_',[
           's'
        ]);

    }

    public function checkDB(Request $request){
        return [
            'redis'=>Redis::connection()->ping('ok'),
            'postgres'=>DB::connection('database_core')->getPdo(),
        ];
    }
}
