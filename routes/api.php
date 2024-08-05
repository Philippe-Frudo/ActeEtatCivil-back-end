<?php

use App\Http\Controllers\ActeController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FonkotanyController;
use App\Http\Controllers\PersonneController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TravailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/regions', RegionController::class);

Route::apiResource('/districts', DistrictController::class);

Route::apiResource('/communes', CommuneController::class);

Route::apiResource('/fonkotany', FonkotanyController::class);

Route::apiResource('/travails', TravailController::class);

Route::apiResource('/personnes', PersonneController::class);

Route::apiResource('/actes', ActeController::class);
