<?php

namespace Core\Social\Http\Controllers;

use Core\Social\Facades\Core;
use Core\Social\Helpers\CoreHelper;
use Core\Social\Models\User;
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
