<?php

namespace App\Http\Controllers;

use App\Models\Donateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreRegisterRequestDonateur;


class DonateurController extends Controller
{
     // Inscription donneur
     public function InscriptionDonneur(StoreRegisterRequestDonateur $request)
     {
        
        $infoUtilisateurValide = $request->validated();
        $imagePath = $request->file('image')->store('images/donateur', 'public');
        $infoUtilisateurValide['password'] = Hash::make($request->password);

        $donateur = Donateur::create([
            "name" => $infoUtilisateurValide['name'],
            "prenom" => $infoUtilisateurValide['prenom'],
            "email" => $infoUtilisateurValide['email'],
            "password" => $infoUtilisateurValide['password'],
            "adresse" => $infoUtilisateurValide['adresse'],
            "cni" => $infoUtilisateurValide['cni'],
            "sexe" => $infoUtilisateurValide['sexe'],
            "image" => $imagePath,
            "telephone" => $infoUtilisateurValide['telephone'],
            "role_id" => 2
            ]);

        // Reponse
        return response()->json([
            "status" => true,
            "message" => "Inscription Donneur réussi",
            "Donneur inscrit"  =>$donateur
        ]);
    }

    // Connexion utilisateur (POST)
    public function loginDonateur(Request $request){
        
        // validation données
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
 
        // JWTAuth
        $token = auth('donateur')->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){
            $donateur = auth('donateur')->user();
            return response()->json([
                "status" => true,
                "message" => "Connexion réuissi",
                "token" => $token,
                "donateur" =>$donateur
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid details"
        ]);
    }

 // Utilisateur Profile (GET)
 public function profile(){

    $userdata = auth('donateur')->user();

    return response()->json([
        "status" => true,
        "message" => "Profile data",
        "data" => $userdata
    ]);
} 

// rafraichir token
public function refreshToken(){
    
    $newToken = auth('donateur')->refresh();

    return response()->json([
        "status" => true,
        "message" => "New access token",
        "token" => $newToken
    ]);
}

// Déconnexion 
public function logout(){
    
    auth('donateur')->logout();

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
    "sexe" => "required",
    "image" => "required",
    "cni" => "required",
    "groupe_sanguin" => "required",
    "password" => "required",
]);

// Mise à jour des informations de l'utilisateur
$user = Donateur::find($id);

if ($user) {
    $user->update([
        "name" => $request->name,
        "email" => $request->email,
        "prenom" => $request->prenom,
        "adresse" => $request->adresse,
        "telephone" => $request->telephone,
        "cni" => $request->cni,
        "image" => $request->image,
        "sexe" => $request->sexe,
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


public function ListeDonateur()
{
    $donateur = Donateur::all();
        return response()->json([
            'liste Stuctures de Santé'=>$donateur,
            
        ]);

}

public function bloquerDonateur($id)
{
    // Recherche de la structure de santé par ID
    $donateur = Donateur::findOrFail($id);

    // Vérifier si la structure est déjà bloquée
    if ($donateur->is_blocked) {
        return response()->json([
            'status' => false,
            'message' => 'Donateur déjà bloqué.'
        ]);
    }

    // Bloquer la structure de santé
    $donateur->update(['is_blocked' => true]);

    return response()->json([
        'status' => true,
        'message' => 'Le Donateur bloqué avec succès.'
    ]);
}


public function afficherDonateursBloques()
{
    $donateursBloques = Donateur::where('is_blocked', true)->get();

    return response()->json([
        'status' => true,
        'message' => 'Liste des donateurs bloqués.',
        'donateurs' => $donateursBloques
    ]);
}

public function afficherDonateursNonBloques()
{
    $donateursNonBloques = Donateur::where('is_blocked', false)->get();

    return response()->json([
        'status' => true,
        'message' => 'Liste des donateurs non bloqués.',
        'donateurs' => $donateursNonBloques
    ]);
}

}
