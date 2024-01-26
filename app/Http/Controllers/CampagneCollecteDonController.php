<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampagneCollecteDon;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CampagneCollecteRequest;

class CampagneCollecteDonController extends Controller
{
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
        ->where('jour', $request->jour)
        ->where('heure', $request->heure)
        ->where('lieu', $request->lieu)
        ->where('statut', 'ouverte')
        ->exists();

      if ($annonceExistante) {
         return response()->json([
         "status" => false,
         "message" => "Une annonce similaire existe déjà pour cette structure."
     ], 422);
 }
        // Créer la campagne
        $campagne = CampagneCollecteDon::create($infoUtilisateurValide);
    
        // Charger la relation StructureSante pour accéder aux détails de la structure
        $campagne->load('StructureSante');
    
        if ($campagne->StructureSante) {
            return response()->json([
                "status" => true,
                "message" => "Annonce publiée avec succès",
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
             $annonces = CampagneCollecteDon::where('is_deleted', 0)
             ->where('structure_id', auth('structure')->user()->id)->paginate(2);
            if($annonces){
                return response()->json([
                    'statut'=>1,
                    'Annonces' => $annonces,
                ]);
            }else{
                return response()->json([
                    'statut'=>0,
                   
                    'Annonces' =>'Aucune annonce enregistrée',
                ]);
            }
           
    
        }


        public function modifierAnnonce(Request $request, $id)
        {
            // validation Données
        $request->validate([
        "jour"=> "required",
        "heure" => "required",
        "lieu" => "required",
        "statut" => "required",
        ]);

        $annonce = CampagneCollecteDon::findOrFail($id);

        if(  $annonce->update($request->all())) {
    
            // Réponse
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
         $annonces = CampagneCollecteDon::where('is_deleted', 0)->paginate(5);
        if($annonces){
            return response()->json([
                'statut'=>1,
                'annonces' => $annonces,
            ]);
        }else{
            return response()->json([
                'statut'=>0,
               
                'projets' =>'Aucune annonce enregistrée',
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
                $annoncestatut = 'complete';
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