<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\StructureController;
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

// routes inscription, connexion
Route::post("InscriptionDonneur", [ApiController::class, "InscriptionDonneur"]);
Route::post("login", [ApiController::class, "login"]);
Route::post("loginStructure", [StructureController::class, "loginStructure"]);


// routes destinées à l'authentification des donneurs et admin
Route::group([
    "middleware" => ["auth:api"]
], function(){
    Route::put("modifierCompte/{id}", [ApiController::class, "modifierCompte"]);
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("refresh", [ApiController::class, "refreshToken"]);
    Route::get("logout", [ApiController::class, "logout"]);
});

// routes destinées à l'authentification des structures de santé
Route::group([
    "middleware" => ["auth:structure"]
], function(){
    Route::put("modifierCompte/{id}", [StructureController::class, "modifier"]);
    Route::get("profileStructure", [StructureController::class, "profileStructure"]);
    Route::get("refresh", [StructureController::class, "refreshToken"]);
    Route::get("logout", [StructureController::class, "logout"]);
});

   // routes destinées à l'admin pour ajouter une structure de santé
Route::middleware("auth:api")->group(function () {
Route::post("ajouterStructureSante", [StructureController::class, "ajouterStructureSante"]);
});

















Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
