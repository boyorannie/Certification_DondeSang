<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Donateur;
use App\Models\StructureSante;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class publierAnnonce extends TestCase
{
    public function testPublierAnnonce()
    {
      
        $structureSante = StructureSante::factory()->create();

        $donateur = Donateur::factory()->create();

        // Utiliser l'authentification de la structure
        $this->actingAs($structureSante, 'structure');

        // Créer une annonce existante (simulée)
        CampagneCollecteDon::factory()->create([
            'structure_id' => $structureSante->id,
            'jour' => '2022-01-30', // Remplacez par la date de votre choix
            'heure' => '12:00:00', // Remplacez par l'heure de votre choix
            'lieu' => 'LieuTest',
            'statut' => 'ouverte',
        ]);

        // Simuler la requête avec des données valides (changez les données en conséquence)
        $response = $this->json('POST', '/votre-endpoint-de-publication', [
            'jour' => '2022-01-31', // Remplacez par la date de votre choix
            'heure' => '14:00:00', // Remplacez par l'heure de votre choix
            'lieu' => 'NouveauLieu',
            // ... autres champs nécessaires ...
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Annonce publiée avec succès',
            ]);

        // Assurez-vous que l'annonce a été créée
        $this->assertDatabaseHas('campagne_collecte_dons', [
            'structure_id' => $structureSante->id,
            'jour' => '2022-01-31', // Remplacez par la date de votre choix
            'heure' => '14:00:00', // Remplacez par l'heure de votre choix
            'lieu' => 'NouveauLieu',
            'statut' => 'ouverte',
        ]);

        // Assurez-vous que le mail a été envoyé au donateur (ne fonctionnera que si le mail est bien configuré)
        Mail::assertSent(function ($mail) use ($donateur) {
            return $mail->hasTo($donateur->email) &&
                $mail->subject === 'Nouvelle Annonce publiée';
        });
    }
}
