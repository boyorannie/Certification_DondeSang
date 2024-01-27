<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\DonateurController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\PromesseDonController;
use App\Http\Controllers\DetailsCollecteController;
use App\Http\Controllers\CampagneCollecteDonController;
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

// routes auxquelles tous les utilisateurs ont accès
Route::post("InscriptionDonneur", [DonateurController::class, "InscriptionDonneur"]);
Route::post("loginDonateur", [DonateurController::class, "loginDonateur"]);
Route::post("loginAdmin", [ApiController::class, "login"]);
Route::post("loginStructure", [StructureController::class, "loginStructure"]);
Route::get("listeAnnonces", [CampagneCollecteDonController::class, "listerAnnonces"]);

//------------------------------------------------------------------------------------------

//routes destinées à l'authentification de l'admin
Route::group([
    "middleware" => ["auth:api"]
], function(){
    Route::get("profileAdmin", [ApiController::class, "profile"]);
    Route::get("refresh", [ApiController::class, "refreshToken"]);
    Route::get("logoutAdmin", [ApiController::class, "logout"]);
});

//---------------------------------------------------------------------------------------------

// routes destinées aux structures de santé
Route::group([
    "middleware" => ["auth:structure"]
], function(){
    Route::post("modifierComptestructure/{id}", [StructureController::class, "modifier"]);
    Route::get("profileStructure", [StructureController::class, "profileStructure"]);
    Route::get("refresh", [StructureController::class, "refreshToken"]);
    Route::get("logoutStructure", [StructureController::class, "logout"]);
    Route::post("publier", [CampagneCollecteDonController::class, "PublierAnnonce"]);
    Route::post("modifierAnnonce/{id}", [CampagneCollecteDonController::class, "modifierAnnonce"]);
    Route::delete("supprimerAnnonce/{annonce}", [CampagneCollecteDonController::class, "SupprimerAnnonce"]);
    Route::get("listerAnnonceStructure", [CampagneCollecteDonController::class, "listerAnnonceStructure"]);
    Route::get("CloturerAnnonce/{id}", [CampagneCollecteDonController::class, "CloturerAnnonce"]);

});
//---------------------------------------------------------------------------------------

// routes destinées aux donateurs
Route::group([
    "middleware" => ["auth:donateur"]
], function(){
    Route::post("modifierCompte/{id}", [DonateurController::class, "modifierCompte"]);
    Route::get("profileDonateur", [DonateurController::class, "profile"]);
    Route::get("refresh", [StructureController::class, "refreshToken"]);
    Route::get("logoutDonateur", [DonateurController::class, "logout"]);
    Route::get("FaireDon/{campagneId}", [PromesseDonController::class, "promesseDon"]);
    Route::put('confirmerpromesse/{promesseDon}', [PromesseDonController::class, 'confirmerPromesseDon']);
    Route::put('annulerpromesse/{promesseDon}', [PromesseDonController::class, 'annulerPromesseDon']);


});




   // routes destinées à l'admin pour ajouter une structure de santé
   Route::group([
    "middleware" => ["checkadmin"]
], function(){
Route::post("ajouterStructureSante", [StructureController::class, "ajouterStructureSante"]);
Route::get("listeAnnoncesAdmin", [CampagneCollecteDonController::class, "listerAnnonces"]);
});







/*
Si la personne n'est pas connecté et 
qu'elle souhaite se déconnecter, ce message 
ci dessus lui est renvoyé

*/
Route::get('/login', function(){
    return response()->json([
        'error' => 'Unauthenticated',
        

    ], 401);
})->name('login');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
