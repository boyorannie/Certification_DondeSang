<?php

namespace App\Http\Controllers\Api;
 
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StructureSante;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreRegisterRequestApiController;

class ApiController extends Controller
{       
    // Connexion utilisateur 
    public function login(Request $request){
        
        // validation données
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
 
        // JWTAuth
        $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){
            $user = auth()->user();
            return response()->json([
                "status" => true,
                "message" => "Connexion réuissi",
                "token" => $token,
                "user" => $user
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Identifiants incorrects"
        ]);
    }

    // Utilisateur Profile (GET)
    public function profile(){

        $userdata = auth('api')->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata
        ]);
    } 

    // rafraichir token
    public function refreshToken(){
        
        $newToken = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $newToken
        ]);
    }

    // Déconnexion utilisateur
    public function logout(){
        
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "Déconnexion réussi"
        ]);
    }

    public function modifierCompte(Request $request, $id)
{
    // validation Données
    $request->validate([
        "name" => "required",
        "email" => "required|email|unique:users,email," . $id,
        "prenom" => "required",
        "adresse" => "required",
        "telephone" => "required",
        "cni" => "required",
        "groupe_sanguin" => "required",
        "password" => "required",
    ]);

    // Mise à jour des informations de l'utilisateur
    $user = User::find($id);

    if ($user) {
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "prenom" => $request->prenom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "cni" => $request->cni,
            "groupe_sanguin" => $request->groupe_sanguin,
            "password" => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Réponse
        return response()->json([
            "status" => true,
            "message" => "Profil mis à jour avec succès",
            "data" => $user,
        ]);
    } else {
        // Utilisateur non trouvé
        return response()->json([
            "status" => false,
            "message" => "Utilisateur non trouvé",
        ]);
    }
}




}