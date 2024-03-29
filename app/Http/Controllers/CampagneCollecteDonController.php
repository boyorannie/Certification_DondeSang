<?php

namespace App\Http\Controllers;

use App\Models\Donateur;
use Illuminate\Http\Request;
use App\Models\CampagneCollecteDon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CampagneCollecteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampagneCollecteDonController extends Controller
{
    use RefreshDatabase;
    

    public function PublierAnnonce(CampagneCollecteRequest $request){
        $structureSante = auth('structure')->user();

        if (!$structureSante) {
            return response()->json([
                "status" => false,
                "message" => "Accès non autorisé. Veuillez vous connectez en tant que structure de santé."
            ], 403);
        }
    
        // Ajouter le structure_id aux données validées avant de créer la campagne
        $infoUtilisateurValide = $request->validated();
        $infoUtilisateurValide['structure_id'] = $structureSante->id;
         // Vérifier si une annonce similaire existe déjà pour cette structure
        $annonceExistante = CampagneCollecteDon::where('structure_id', $structureSante->id)
        ->where('date', $request->date)
        // ->where('heure', $request->heure)
        ->where('lieu', $request->lieu)
        ->where('statut', 'ouverte')
        ->exists();

      if ($annonceExistante) {
         return response()->json([
         "status" => false,
         "message" => "Une annonce similaire existe déjà pour cette structure."
     ], 422);
 }
        
        $campagne = CampagneCollecteDon::create($infoUtilisateurValide);
    
        // Charger la relation StructureSante pour accéder aux détails de la structure
        $campagne->load('StructureSante');
    
        if ($campagne->StructureSante) {
            $donateurs = Donateur::all();

            // Envoyer l'e-mail à chaque donateur
            foreach ($donateurs as $donateur) {
                // Mail::to($donateur->email)->send(new mail($campagne));
                Mail::send('nouvelle_annonce', ['donateur'=> $donateur],function ($message) use ($donateur) {
                    $message->to($donateur->email);
                    $message->subject('Nouvelle Annonce publiée');
                });
            }
            return response()->json([
                "status" => true,
                "message" => "Annonce publiée avec succès",
                "Annonce" => $campagne,
                "Structure de Santé" => $campagne->StructureSante
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "L\'annonce n\'est associée à aucune structure de santé."
            ]);
        }
        }


        public function listerAnnonceStructure()
        {
         try {
        $annonces = CampagneCollecteDon::where('is_deleted', 0)
            ->where('structure_id', auth('structure')->user()->id);

        if ($annonces->count() > 0) {
            return response()->json([
                'statut_code' => 200,
                'statut_message' => 'Liste des annonces de la structure',
                'data' => $annonces,
            ]);
        } else {
            return response()->json([
                'statut_code' => 204, 
                'statut_message' => 'Aucune annonce enregistrée pour cette structure',
                'data' => null,
            ]);
        }
       }    catch (\Exception $e) {
            return response()->json([
            'statut_code' => 500,
            'statut_message' => 'Erreur lors de la récupération des annonces de la structure',
            'error' => $e->getMessage(),
        ]);
    }
}



        public function modifierAnnonce(Request $request, $id)
        {
        
       $annoncevalider= $request->validate([
        "date"=> "required",
        // "heure" => "required",
        "lieu" => "required",
        "statut" => "required",
        ]);

        $annonce = CampagneCollecteDon::findOrFail($id);
        $annonce->date = $annoncevalider['date'];
        // $annonce->heure = $annoncevalider['heure'];
        $annonce->lieu = $annoncevalider['lieu'];
        $annonce->statut = $annoncevalider['statut'];
        if($annonce->update()) {
    
            
            return response()->json([
                "status" => true,
                "message" => "Annonce modifiée avec succès",
                "détails Annonce" => $annonce,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Annonce non trouvée",
            ]);
        }
    }




    public function listerAnnonces()
    {
        try {
            $annonces = CampagneCollecteDon::where('is_deleted', 0);
    
            if ($annonces->count() > 0) {
                return response()->json([
                    'statut_code' => 200,
                    'statut_message' => 'Liste des annonces',
                    'data' => $annonces,
                ]);
            } else {
                return response()->json([
                    'statut_code' => 204, 
                    'statut_message' => 'Aucune annonce enregistrée',
                    'data' => null,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'statut_code' => 500,
                'statut_message' => 'Erreur lors de la récupération des annonces',
                'error' => $e->getMessage(),
            ]);
        }
    }
    

        public function SupprimerAnnonce(CampagneCollecteDon $annonce)
        {
            if($annonce){
                $annonce->is_deleted =1;
                
                if($annonce->save()){
                    return response()->json([
                        "status" => 1,
                        "message" => "Annonce supprimée avec succès"
                    ], 201);
                } else {
                    return response()->json([
                        "status" => 0,
                        "message" => "Echec suppression"
                    ],404);
                }
            }
        }

        public function CloturerAnnonce(Request $request,  $id)
    {
        
            $annonce= CampagneCollecteDon::findOrFail($id);
                if($annonce){
                $annonce->statut = 'complete';
                if($annonce->update()){
                    return response()->json([
                        "status" => 1,
                        "message" => "Annonce cloturée avec succès"
                    ], 201);
                } else {
                    return response()->json([
                        "status" => 0,
                        "message" => "Echec cloture"
                    ],404);
                }
            }
        }

        

        

        }
        
    