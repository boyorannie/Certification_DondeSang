<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    // Inscription donneur
    public function InscriptionDonneur(Request $request){
        
        // data validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "prenom" => "required",
            "cni" => "required",
            "groupe_sanguin" => "required",
            "password" => "required",
            
        ]);

        // User Model
        User::create([      
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "prenom" => $request->prenom,
            "cni" => $request->cni,
            "groupe_sanguin" => $request->groupe_sanguin,
            "role_id" =>2,
        ]);

        // Response
        return response()->json([
            "status" => true,
            "message" => "Inscription Donneur réussi"
        ]);
    }

    // User Login (POST, formdata)
    public function login(Request $request){
        
        // data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // JWTAuth
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){

            return response()->json([
                "status" => true,
                "message" => "Connexion réuissi",
                "token" => $token
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid details"
        ]);
    }

    // User Profile (GET)
    public function profile(){

        $userdata = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata
        ]);
    } 

    // To generate refresh token value
    public function refreshToken(){
        
        $newToken = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $newToken
        ]);
    }

    // User Logout (GET)
    public function logout(){
        
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "Déconnexion réussi"
        ]);
    }

    public function modifierCompte(Request $request, $id)
{
    // Data validation
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