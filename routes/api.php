<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScripController;
use App\Http\Controllers\IndustryController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::post('scrips', [ScripController::class, 'getAllScrips']);
    Route::get('groups', [ScripController::class, 'getGroups']);

    // Route::resource('scrips', ScripController::class);
    // Route::resource('setup', ScripController::class)->only(['setup']);
});

Route::get('setup', [ScripController::class, 'setup']);
Route::get('setupIndustry', [IndustryController::class, 'setup']);
Route::get('industries', [IndustryController::class, 'index']);
