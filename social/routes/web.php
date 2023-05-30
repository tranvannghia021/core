<?php

$namespace='Core\Social\Http\Controllers';
Route::namespace($namespace)->get('/verify',[\Core\Social\Http\Controllers\AppController::class,'verify'])->middleware('auth.verify');
