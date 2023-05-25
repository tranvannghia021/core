<?php

namespace App\Http\Controllers;

use App\Helpers\CoreHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class DebugController extends Controller
{
    public function test(Request $request){

      dd(CoreHelper::ip());

    }

    public function checkDB(Request $request){
        return [
            'redis'=>Redis::connection()->ping('ok'),
            'postgres'=>DB::connection('database_core')->getPdo(),
        ];
    }
}
