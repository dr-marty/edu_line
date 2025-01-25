<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\LineMessageController;

// phpinfo();

Route::get('/', [UserController::class, 'top']);

