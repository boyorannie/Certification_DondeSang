<?php

namespace App\Http\Controllers;

use App\Models\PromesseDon;
use Illuminate\Http\Request;
use App\Models\CampagneCollecteDon;

class PromesseDonController extends Controller
{
    public function promesseDon($campagneId)
    {
      
        $donateur = auth('donateur')->user(); 

        // Vérifie si le donateur existe
        if (!$donateur) {
            return response()->json([
                "status" => false,
                "message" => "Donateur non trouvé. Veuillez vous connecter en tant que donateur."
            ], 403);
        }
       

        // Vérifie si la campagne existe
        $campagne = CampagneCollecteDon::find($campagneId);

        if (!$campagne) {
            return response()->json([
                "status" => false,
                "message" => "Campagne non trouvée."
            ], 404);
        }

        // Créer la promesse de don
        $promesseDon = PromesseDon::create([
            'campagne_id' => $campagne->id,
            'donateur_id' => $donateur->id,
            'statut' => 'en attente',
        ]);

        return response()->json([
            "status" => true,
            "message" => "Promesse de don créée avec succès.",
            "details" => [
                "donateur" => $donateur,
                "campagne" => $campagne,
                "promesse_don" => $promesseDon
            ]
        ]);
    }

    public function confirmerPromesseDon(PromesseDon $promesseDon)
    {
        
        $promesseDon->update(['statut' => 'confirmé']);

        return response()->json([
            "status" => true,
            "message" => "Promesse de don confirmée avec succès.",
            "details" => [
                "promesse_don" => $promesseDon
            ]
        ]);
    }

    public function annulerPromesseDon(PromesseDon  $promesseDon)
    {
       
        $promesseDon->update(['statut' => 'annulé']);

        return response()->json([
            "status" => true,
            "message" => "Promesse de don refusée avec succès.",
            "details" => [
                "promesse_don" => $promesseDon
            ]
        ]);
    }

    public function ListePromesseDonConfirme()
{
    $promesseconfirme = PromesseDon::where('statut', 'confirmé')->get();

    return response()->json([
        'status' => true,
        'message' => 'Liste des Promesses de Don Confirmées.',
        'donateurs' => $promesseconfirme
    ]);
}
public function testListePromesseDonConfirme()
{
   
    PromesseDon::factory()->count(3)->create(['statut' => 'confirmé']);

    // Appeler la fonction pour obtenir la liste des promesses de don confirmées
    $response = $this->json('GET', 'api/listePromesseDonConfirme');

    // Vérifier que la réponse est 200 OK
    $response->assertStatus(200);

    // Vérifier que la structure de la réponse est correcte
    $response->assertJsonStructure([
        'status',
        'message',
        'donateurs' => [
            '*' => [
                'id',
                'statut',
               
            ]
        ]
    ]);

    // Vérifier que le nombre de promesses de don confirmées dans la réponse est correct
    $response->assertJsonCount(3, 'donateurs');
}
}
    