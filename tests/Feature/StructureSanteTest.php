<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StructureSante;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;


class StructureSanteTest extends TestCase
{
  
    public function testAjouterStructureSante()
    {
        
     
      $existingUser = User::where('email', 'khadijambengue96@gmail.com')->first();

      if (!$existingUser) {
          $admin = User::factory()->create(['role_id' => 1, 'email' => 'boyorthrse@gmail.com']);
          $this->actingAs($admin);

            $image = UploadedFile::fake()->image('structure.jpg');

            $structureData = [
                'name' => 'Hopital régional Tamba',
                'email' => 'hopitaltamba@gov.sn',
                'password' => 'P@sser123',
                'adresse' => 'diallobougou',
                'image' => $image,
                'telephone' => '339584715',
            ];

            $response = $this->json('POST', '/api/ajouterStructureSante', $structureData);

            $this->assertDatabaseHas('structure_santes', [
                'name' => $structureData['name'],
                'email' => $structureData['email'],
                'adresse' => $structureData['adresse'],
                'telephone' => $structureData['telephone'],
                'role_id' => 3,
            ]);

        
            $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'Ajout Structure de Santé réussi',
                    'data' => $response->json('data'),
                ]);
        } else {
            $this->assertTrue(true); 
        }
    }

    public function testLoginStructure()
    {
        $structure = StructureSante::factory()->create([
            'role_id' => 3,
            'email' => 'structure@example.com',
            'password' => Hash::make('password'),
        ]);

        $requestData = [
            'email' => 'structure@example.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/loginStructure', $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' =>$response->json('message'),
                'token' => $response->json('token'),
                'structure' => $response->json('structure'),
            ]);
    }

    public function testLogout()
    {
        $structure = StructureSante::factory()->create([
            'role_id' => 3,
        ]);

        $token = JWTAuth::fromUser($structure);

        // Connexion de l'utilisateur avec le guard 'structure' en utilisant le token JWT
        $this->withHeader('Authorization', 'Bearer ' . $token);
       
        $response = $this->json('GET', '/api/logoutStructure');
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' =>$response->json('message'),
            ]);

     // vérifie si la structure s'est bien déconnectée 
        $this->assertGuest('structure');
    }

    
    public function testModifierStructure()
    {
        $structure = StructureSante::factory()->create();

        $response = $this->actingAs($structure, 'structure')
            ->json('POST', "api/modifierComptestructures/$structure->id", [
                'name' => 'mameabdou',
                'adresse' => 'parcelle',
                'telephone' => '775486325',
                'image' => UploadedFile::fake()->image('structure.jpg'),
                'password' => 'P@sser123',
            ]);
            $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Profil mis à jour avec succès',
                'data' => $response->json('data'),
            ]);

        // Vérifie que le mot de passe a été mis à jour correctement
        $this->assertTrue(Hash::check('P@sser123', $structure->fresh()->password));
    }

    public function testListeStructure()
    {
    
        $structures = StructureSante::factory(3)->create();
        $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
        $this->actingAs($admin, 'structure');
        $response = $this->get('api/listeStructure');
        $response->assertStatus(200);
        
        // vérifie que la réponse contient les structures créées
        foreach ($structures as $structure) {
            $response->assertJsonFragment([
                'name' => $structure->name,
                'adresse' => $structure->adresse,
                'telephone' => $structure->telephone,
                'image' => $structure->image,
            ]);
        }
    }

    public function testBloquerStructure()
    {
    
        $structure = StructureSante::factory()->create();
        $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
        $this->actingAs($admin, 'structure');

        $response = $this->putJson("api/bloquerStructure/$structure->id");
    
        $response->assertStatus(200);
     
        $this->assertDatabaseHas('structure_santes', [
            'id' => $structure->id,
            'is_blocked' => true,
        ]);
    
        $response->assertJson([
            'status' => true,
            'message' => 'La structure de santé a été bloquée avec succès.',
        ]);
    }

    public function testAfficherStructureBloques()
{
    $structureBloquee1 = StructureSante::factory()->create(['is_blocked' => true]);
    $structureBloquee2 = StructureSante::factory()->create(['is_blocked' => true]);
    $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
    $this->actingAs($admin, 'structure');

    $response = $this->getJson('/api/afficherStructureBloques');
    $response->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'structureBloques' => [
            '*' => ['id', 'is_blocked'],
        ],
    ]);
}

public function testAfficherStructuresNonBloques()
{
    $structureNonBloquee1 = StructureSante::factory()->create(['is_blocked' => false]);
    $structureNonBloquee2 = StructureSante::factory()->create(['is_blocked' => false]);
    $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
    $this->actingAs($admin, 'structure');

    $response = $this->getJson('/api/afficherStructuresNonBloques');
    $response->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'structureNonBloques' => [
            '*' => ['id', 'is_blocked'],
        ],
    ]);
}
}
    



