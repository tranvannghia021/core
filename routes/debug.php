<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebugController;

Route::get('check',[DebugController::class,'checkDB']);

Route::get('test',[DebugController::class,'test']);
