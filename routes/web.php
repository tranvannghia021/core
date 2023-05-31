<?php

$namespace='Devtvn\Social\Http\Controllers';
Route::namespace($namespace)->get('/verify',[\Devtvn\Social\Http\Controllers\AppController::class,'verify'])->middleware('auth.verify');
