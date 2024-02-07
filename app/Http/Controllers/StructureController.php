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
            $structure = auth('structure')->user();
            return response()->json([
                "status" => true,
                "message" => "Connexion réuissi",
                "token" => $token,
                "structure" =>$structure
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

    public function modifierStructure(Request $request, $id)
{
    // Validation des données
    $request->validate([
        "name" => "required",
        "adresse" => "required",
        "telephone" => "required",
        "image" => "required|image", 
        "password" => "required",
    ]);

    $user = StructureSante::find($id);

    if ($user) {
        $imagePath = $request->file('image')->store('images/structure', 'public');
        
        // Mise à jour des données
        $user->update([
            "name" => $request->name,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "image" => $imagePath,
            "password" => Hash::make($request->password),
        ]);

        return response()->json([
            "status" => true,
            "message" => "Profil mis à jour avec succès",
            "data" => $user,
        ], 200);
    } else {
       
        return response()->json([
            "status" => false,
            "message" => "Structure non trouvée",
        ], 404);
    }
}


public function ListeStructure()
{
    try{
         return response()->json([
            'statut_code' =>200,
            'statut_message' => 'Liste Structures Santes inscrites',
            'data'=>StructureSante::all()
            
        ]);

    }catch(Exception $e){
     return reponse($e)->json($e);
    }
  
        
}

public function bloquerStructure($id)
{
    // Recherche de la structure de santé par ID
    $structure = StructureSante::findOrFail($id);

    // Vérifier si la structure est déjà bloquée
    if ($structure->is_blocked) {
        return response()->json([
            'status' => false,
            'message' => 'La structure de santé est déjà bloquée.'
        ]);
    }

    // Bloquer la structure de santé
    $structure->update(['is_blocked' => true]);

    return response()->json([
        'status' => true,
        'message' => 'La structure de santé a été bloquée avec succès.'
    ]);
}

public function afficherStructureBloques()
{
    $structureBloques = StructureSante::where('is_blocked', true)->get();

    return response()->json([
        'status' => true,
        'message' => 'Liste des structures bloquées.',
        'structureBloques' => $structureBloques
    ]);
}

public function afficherStructuresNonBloques()
{
    $structureNonBloques = StructureSante::where('is_blocked', false)->get();

    return response()->json([
        'status' => true,
        'message' => 'Liste des structures non bloquées.',
        'structureNonBloques' => $structureNonBloques
    ]);
}

}