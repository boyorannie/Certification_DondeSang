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
            'jour' => 'Mardi', 
            'heure' => '13:00:00', 
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
            'jour' => 'Mardi', 
            'heure' => '13:00:00', 
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
        'jour' => 'Lundi',
        'heure' => '12:00:00',
        'lieu' => 'Hlm',
        'statut' => 'ouverte',
    ]);

    $annonce = CampagneCollecteDon::factory()->create([
        'structure_id' => $structureSante->id,
        'jour' => 'Mardi',
        'heure' => '14:00:00',
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
