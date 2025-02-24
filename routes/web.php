<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\LineMessageController;
use App\Http\Controllers\RichMenuController;

// phpinfo();
//api.phpではなく、web.phpでUI(ユーザインターフェイスを構築する)


Route::get('/', [UserController::class, 'top']);

Route::get('/rich-menu', [RichMenuController::class, 'index'])->name('richmenu');
Route::post('/rich-menu/create', [RichMenuController::class, 'create'])->name('richmenu_create');
