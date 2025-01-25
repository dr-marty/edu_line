<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LineMessageController;
use App\Http\Controllers\RichMenuController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('/line/webhook', [LineMessageController::class, 'reply']);


Route::prefix('/line')->group(function(){
    Route::post('/webhook', [LineMessageController::class, 'reply']);
    Route::prefix('richmenu')->group(function () {
        Route::post('/create', [RichMenuController::class, 'createRichMenu']);
        Route::post('/upload/{richMenuId}', [RichMenuController::class, 'uploadRichMenuImage']);
        Route::post('/set-default/{richMenuId}', [RichMenuController::class, 'setDefaultRichMenu']);
        Route::post('/create-alias', [RichMenuController::class, 'createRichMenuAlias']);
        Route::post('/switch', [RichMenuController::class, 'createTabSwitchingRichMenus']);
    });
});
// Route::prefix('richmenu')->group(function () {
//     Route::post('/create', [RichMenuController::class, 'createRichMenu']);
//     Route::post('/upload/{richMenuId}', [RichMenuController::class, 'uploadRichMenuImage']);
//     Route::post('/set-default/{richMenuId}', [RichMenuController::class, 'setDefaultRichMenu']);
//     Route::post('/create-alias', [RichMenuController::class, 'createRichMenuAlias']);
//     Route::post('/switch', [RichMenuController::class, 'createTabSwitchingRichMenus']);
// });
