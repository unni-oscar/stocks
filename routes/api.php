<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScripController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\BhavcopyController;
use App\Http\Controllers\SectorController;


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
    Route::get('setMasterFile', [IndustryController::class, 'setScripMaster']);

    Route::get('sectors', [SectorController::class, 'index']);
    Route::get('setSectors', [SectorController::class, 'setSectors']);

    Route::get('industries', [IndustryController::class, 'index']);
    Route::get('setIndustries', [IndustryController::class, 'setIndustries']);

    Route::get('industriesForSector/{id}', [IndustryController::class, 'getIndustriesForSector']);

    
    
    // getbhavcopy

    // Route::resource('scrips', ScripController::class);
    // Route::resource('setup', ScripController::class)->only(['setup']);
});

Route::get('setup', [ScripController::class, 'setup']);
Route::get('setupIndustry', [IndustryController::class, 'setup']);
