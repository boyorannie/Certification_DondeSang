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
            'date' => '2024-02-05 14:00:00',  
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
            'date' => '2024-02-05 14:00:00', 
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
        'date' => '2024-02-05 15:00:00',
        'lieu' => 'Hlm',
        'statut' => 'ouverte',
    ]);

    $annonce2 = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'date' => '2024-02-06 16:00:00',
        'lieu' => 'Centre ville',
        'statut' => 'ouverte',
    ]);

    $response = $this->getJson('/api/listerAnnonceStructure');
    $response->assertStatus(200)
        ->assertJson([
            'statut_code' => 200,
            'statut_message' => 'Liste des annonces de la structure',
            'data'=>$response->json('data')
            
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
        'date' => '2024-03-15 09:00:00',
        'lieu' => 'Mame abdou',
        'statut' => 'ouverte',
    ];

    $response = $this->postJson("api/modifierAnnonce/$annonce->id", $nouvellesDonnees);
    
    $response->assertStatus(200);

    $this->assertDatabaseHas('campagne_collecte_dons',[
        'date' => '2024-03-15 09:00:00',
        'lieu' => 'Mame abdou',
        'statut' => 'ouverte',
    ]);
}

public function testSupprimerAnnonce()
{
    $structureSante = StructureSante::factory()->create();
    $this->actingAs($structureSante, 'structure');

    $annonce = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'is_deleted' => false,
    ]);

    $response = $this->json('DELETE', "api/supprimerAnnonce/$annonce->id");
    
    $response->assertStatus(201)
        ->assertJson([
            'status' => 1,
            'message' => 'Annonce supprimée avec succès',
        ]);

    $this->assertDatabaseHas('campagne_collecte_dons', [
        'id' => $annonce->id,
        'is_deleted' => true,
    ]);
}
public function testCloturerAnnonce()
{
    $structureSante = StructureSante::factory()->create();
    $this->actingAs($structureSante, 'structure');

    $annonce = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'statut' =>'ouverte',
    ]);

    $response = $this->json('GET', "api/CloturerAnnonce/$annonce->id");
    
    $response->assertStatus(201)
        ->assertJson([
            'status' => 1,
            'message' => 'Annonce cloturée avec succès',
        ]);
        
    $this->assertDatabaseHas('campagne_collecte_dons', [
        'id' => $annonce->id,
        'statut' =>'complete',
    ]);
}

public function testListerAnnonce()
{
    
    $structureSante = StructureSante::factory()->create();
    $annonce = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'date' => '2024-02-07',
        'lieu' => 'Hlm',
        'statut' => 'ouverte',
    ]);

    $annonce = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'date' => '2024-02-08',
        'lieu' => 'Centre ville',
        'statut' => 'ouverte',
    ]);

    $response = $this->getJson('/api/listeAnnonces');
    $response->assertStatus(200)
        ->assertJson([
            'statut_code' => 200,
            'statut_message' => 'Liste des annonces',
            'data'=>$response->json('data')
            
        ]);
}

}
