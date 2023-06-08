<?php
use \Devtvn\Social\Http\Controllers\CoreController;

Route::group(['prefix'=>'api','middleware'=>'global'],function(){
    Route::prefix('{platform}')->controller(CoreController::class)->group(function(){
        Route::post('generate-url', 'generateUrl');
    });
    Route::get('handle/auth', [CoreController::class,'handleAuth'])->middleware(['social.auth','core.shopify']);
    
    Route::prefix('app')->controller(\Devtvn\Social\Http\Controllers\AppController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('refresh','refresh')
            ->withoutMiddleware(\Devtvn\Social\Http\Middleware\GlobalJwtMiddleware::class)
            ->middleware('refresh');
        Route::middleware('core')->group(function () {
            Route::delete('delete','delete');
            Route::get('info', 'user');
            Route::post('info', 'updateUser');
            Route::put('change-password', 'changePassword');
        });
        Route::post('forgot-password','reset');
        Route::post('re-send', 'reSendLinkEmail');
    });
    
});