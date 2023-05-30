<?php
use \Devtvn\Social\Http\Controllers\DebugController;
$namespace='Devtvn\Social\Http\Controllers';
Route::prefix(
    'debug'
)->namespace($namespace)->group(function () {
    Route::get('check',[DebugController::class,'checkDB']);

    Route::get('test',[DebugController::class,'test']);
});

