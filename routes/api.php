<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
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

// routes inscription, connexion et déconnexion 
Route::post("InscriptionDonneur", [ApiController::class, "InscriptionDonneur"]);
Route::post("login", [ApiController::class, "login"]);

// routes destinées à l'authentification
Route::group([
    "middleware" => ["auth:api"]
], function(){
    Route::put("modifierCompte/{id}", [ApiController::class, "modifierCompte"]);
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("refresh", [ApiController::class, "refreshToken"]);
    Route::get("logout", [ApiController::class, "logout"]);
});
   // Votre route pour ajouter une structure de santé
Route::middleware(['checkadmin', "auth:api"])->group(function () {
  Route::post("ajoutStructure", [ApiController::class, "ajouterStructureSante"]);
});

















Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
