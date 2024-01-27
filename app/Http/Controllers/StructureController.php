<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StructureSante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreRegisterRequestStructure;

class StructureController extends Controller
{
  
    public function ajouterStructureSante(StoreRegisterRequestStructure $request)
    { 
        $infoUtilisateurValide = $request->validated();
        $infoUtilisateurValide['password'] = Hash::make($request->password);
        $imagePath = $request->file('image')->store('images/structure', 'public');

        // Utilisation du guard 'structures' pour l'authentification
        $structure = StructureSante::create([
            "name" => $infoUtilisateurValide['name'],
            "email" => $infoUtilisateurValide['email'],
            "password" => $infoUtilisateurValide['password'],
            "adresse" => $infoUtilisateurValide['adresse'],
            "image" => $imagePath,
            "telephone" => $infoUtilisateurValide['telephone'],
            "role_id" => 3,
        ]);

        return response()->json([
            "status" => true,
            "message" => "Ajout Structure de Santé réussi",
            "Détails Structure" => $structure,
        ]);
    }
        
    public function loginStructure(Request $request){
        
        // validation données
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // JWTAuth
        $token = auth('structure')->attempt([
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

     // Utilisateur Profile (GET)
     public function profileStructure(){

        $userdata = auth('structure')->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata
        ]);
    } 
    // rafraichir token
    public function refreshToken(){
        
        $newToken = auth('structure')->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $newToken
        ]);
    }

    // Déconnexion utilisateur
    public function logout(){
        
        auth('structure')->logout();

        return response()->json([
            "status" => true,
            "message" => "Déconnexion réussi"
        ]);
    }

    public function modifier(Request $request, $id)
{
    // validation Données
    $request->validate([
        "name" => "required",
        "email" => "required|email|unique:users,email," . $id,
        "adresse" => "required",
        "telephone" => "required",
        "image" => "required",
        "password" => "required",
    ]);

    // Mise à jour des informations de l'utilisateur
    $user = StructureSante::find($id);

    if ($user) {
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "image" => $request->image,
            "password" => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Réponse
        return response()->json([
            "status" => true,
            "message" => "Profil mis à jour avec succès",
            "data" => $user,
        ]);
    } else {
        // Structure non trouvée
        return response()->json([
            "status" => false,
            "message" => "Structure non trouvée",
        ]);
    }
}
}
