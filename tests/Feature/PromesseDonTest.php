<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Donateur;
use App\Models\PromesseDon;
use App\Models\CampagneCollecteDon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromesseDonTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testPromesseDon()
    {
        $donateur = Donateur::factory()->create();
        $campagne = CampagneCollecteDon::factory()->create();
    
        $this->actingAs($donateur, 'donateur');
    
        // Appeler la fonction promesseDon avec l'ID de la campagne
        $response = $this->json('GET', "api/FaireDon/$campagne->id");
    
        $response->assertStatus(200)
        
        // Assurer que la réponse contient les clés "status" et "message"
        ->assertJsonStructure([
            'status',
            'message',
            'details' => [
                'donateur',
                'campagne',
                'promesse_don'
            ]
        ])
        
        // Assurer que la réponse contient le message attendu
        ->assertJson([
            'status' => true,
            'message' => 'Promesse de don créée avec succès.'
        ]);
    }
    
    public function testConfirmerPromesseDon()
    {
        $donateur = Donateur::factory()->create();
        $this->actingAs($donateur, 'donateur');
        $promesseDon = PromesseDon::factory()->create(['statut' => 'en attente']);
        
        $response = $this->json('PUT', "api/confirmerpromesse/$promesseDon->id");
        $response->assertStatus(200);
    
        // Vérifier la structure de la réponse JSON
        $response->assertJsonStructure([
            'status',
            'message',
            'details' => [
                'promesse_don' => [
                    'id',
                    'statut',
                ]
            ]
        ]);
    
        // Vérifier que le statut de la promesse de don a été mis à jour à "confirmé"
        $this->assertEquals('confirmé', $promesseDon->fresh()->statut);
    }
    
    public function testAnnulerPromesseDon()
{
    $donateur = Donateur::factory()->create();
    
    $this->actingAs($donateur, 'donateur');
    
    $promesseDon = PromesseDon::factory()->create(['statut' => 'en attente']);
    
    $response = $this->json('PUT', "api/annulerpromesse/$promesseDon->id");

    $response->assertStatus(200);

    // Vérifier que la structure de la réponse est correcte
    $response->assertJsonStructure([
        'status',
        'message',
        'details' => [
            'promesse_don' => [
                'id',
                'statut',
            ]
        ]
    ]);

    // Vérifier que le statut de la promesse de don a été mis à jour à "annulé"
    $this->assertEquals('annulé', $promesseDon->fresh()->statut);
}

public function testListePromesseDonConfirme()
{
    $donateur = Donateur::factory()->create();
    
    $this->actingAs($donateur, 'donateur');
    PromesseDon::factory()->count(3)->create(['statut' => 'confirmé']);

    $response = $this->json('GET', 'api/ListePromesseDonConfirme');

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

  
}

}
