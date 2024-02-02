<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Donateur;
use App\Models\StructureSante;
use App\Models\CampagneCollecteDon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampagneCollecteTest extends TestCase
{
    public function testPublierAnnonce()
    {
      
        $structureSante = StructureSante::factory()->create();

        $donateur = Donateur::factory()->create();
        $this->actingAs($structureSante, 'structure');
        $this->assertTrue(auth('structure')->check());
        $this->assertInstanceOf(StructureSante::class, auth('structure')->user());
        $response = $this->postJson('api/publier', [
            'jour' => 'Samedi', 
            'heure' => '12:00:00', 
            'lieu' => 'Hlm',
            'statut' => 'ouverte',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Annonce publiée avec succès',
            ]);

        $this->assertDatabaseHas('campagne_collecte_dons', [
            'structure_id' => $structureSante->id,
            'jour' => 'Samedi', 
            'heure' => '12:00:00', 
            'lieu' => 'Hlm',
            'statut' => 'ouverte',
        ]);

      
}

public function testListerAnnonceStructure()
{
    
    $structureSante = StructureSante::factory()->create();
    $this->actingAs($structureSante, 'structure');
    $this->assertTrue(auth('structure')->check());
    $this->assertInstanceOf(StructureSante::class, auth('structure')->user());

    $annonce1 = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'jour' => 'Lundi',
        'heure' => '12:00:00',
        'lieu' => 'Hlm',
        'statut' => 'ouverte',
    ]);

    $annonce2 = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'jour' => 'Mardi',
        'heure' => '14:00:00',
        'lieu' => 'Centre ville',
        'statut' => 'ouverte',
    ]);

    $response = $this->getJson('/api/listerAnnonceStructure');
    $response->assertStatus(200)
        ->assertJson([
            'statut_code' => 200,
            'statut_message' => 'Liste des annonces de la structure',
            'data' => [
                'current_page' => 1,
                'data' => [
                    [
                        'id' => $annonce1->id,
                        'jour' => 'Lundi',
                        'heure' => '12:00:00',
                        'lieu' => 'Hlm',
                        'statut' => 'ouverte',
                    ],
                    [
                        'id' => $annonce2->id,
                        'jour' => 'Mardi',
                        'heure' => '14:00:00',
                        'lieu' => 'Centre ville',
                        'statut' => 'ouverte',
                    ],
                   
                ],
                
            ],
        ]);
}

public function testModifierAnnonce()
{
    $structureSante = StructureSante::factory()->create();
    $this->actingAs($structureSante, 'structure');
    $annonce = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
    ]);
    $nouvellesDonnees = [
        'jour' => 'Dimanche',
        'heure' => '08:00:00',
        'lieu' => 'Mame abdou',
        'statut' => 'ouverte',
    ];

    $response = $this->postJson("api/modifierAnnonce/$annonce->id", $nouvellesDonnees);
    
    $response->assertStatus(200);

    $this->assertDatabaseHas('campagne_collecte_dons',[
        'jour' => 'Dimanche',
        'heure' => '08:00:00',
        'lieu' => 'Mame abdou',
        'statut' => 'ouverte',
    ]);
}



}
