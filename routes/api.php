<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\AppController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=>'{platform}'],function (){
    Route::post('generate-url',[CoreController::class,'generateUrl']);
    Route::get('handle/auth',[CoreController::class,'handleAuth'])->middleware('social.auth');
});

Route::group(['prefix'=>'app'],function (){
    Route::post('login',[AppController::class,'login']);
    Route::post('register',[AppController::class,'register']);
    Route::delete('delete',[AppController::class,'delete']);
    Route::get('info',[AppController::class,'user']);
    Route::post('re-send',[AppController::class,'reSendLinkEmail']);
    Route::get('verify',[AppController::class,'verify']);
});
