<?php

use App\Http\Controllers\ActeController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FonkotanyController;
use App\Http\Controllers\OfficierController;
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

// ============= ROUTE OFFICIER (UTILISATEUR) ============== ====
Route::apiResource('/officiers', OfficierController::class);

Route::post('/officiers/auth', [OfficierController::class, 'authentication']);

Route::post('/officiers/verifyConnect', [OfficierController::class, 'verifyConnect']);

Route::post('/officiers/confirm', [OfficierController::class, 'confirmOfficier']);

Route::post('/officiers/delete', [OfficierController::class, 'deleteOfficier']);

Route::post('/officiers/logOut', [OfficierController::class, 'logOut']);

Route::get('/nombreOfficier', [OfficierController::class, 'nombreOfficier']);



// ============= ROUTE REGION ===================
Route::apiResource('/regions', RegionController::class);

Route::post('/addAllRegion', [RegionController::class, 'addAllRegion']);


Route::apiResource('/districts', DistrictController::class);

Route::post('/addAlldistrict', [DistrictController::class, 'addAlldistrict']);


Route::apiResource('/communes', CommuneController::class);

Route::post('/addAllcommune', [CommuneController::class, 'addAllcommune']);


Route::apiResource('/fonkotany', FonkotanyController::class);

Route::post('/addAllfonkotany', [FonkotanyController::class, 'addAllfonkotany']);


Route::apiResource('/travails', TravailController::class);

Route::post('/addAlltravail', [TravailController::class, 'addAlltravail']);

Route::put('travails/{id_tavail}', [TravailController::class, 'update']);


Route::apiResource('/personnes', PersonneController::class);

Route::get('/lastPersonne', [PersonneController::class, 'lastPersonne']);




Route::apiResource('/actes', ActeController::class);

Route::get('/getDetail/{id_acte}', [ActeController::class, "getDetail"]);

Route::post('/findNum', [ActeController::class, "verifyNumActe"]);


Route::get('/typesActe', [ActeController::class, 'getTypesActe']);

Route::get('/countNaissance', [ActeController::class, 'countNaissance']);


Route::get('/yearBirthday', [ActeController::class, 'groupBirthday']);

Route::get('/registerToday', [ActeController::class, 'registerToday']);

Route::get('/getEnregistrementsParMois', [ActeController::class, 'getEnregistrementsParMois']);

Route::get('/groupBirthdayWithCommune', [ActeController::class, 'groupBirthdayWithCommune']);
// Route::get('/getAll', [ActeController::class, 'getAll']);