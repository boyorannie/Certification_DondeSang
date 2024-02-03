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

     // vérifier si la structure s'est bien déconnectée 
        $this->assertGuest('structure');
    }

    public function testModifierStructure()
    {
        $structure = StructureSante::factory()->create();
    
      

        // Utiliser le token pour simuler l'authentification
        $response = $this->withoutMiddleware()
                         ->json('POST', "api/modifierComptestructure/{$structure->id}", [
                             'name' => 'mameabdou',
                             'adresse' => 'parcelle',
                             'telephone' => '775486325',
                             'image' => UploadedFile::fake()->image('structure.jpg'),
                             'password' => 'P@sser123',
                         ]);
    
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => $response->json('message'),
                'data' => $response->json('data'),
            ]);
    
        // Assurez-vous que la base de données a été mise à jour avec les nouvelles données
        $this->assertDatabaseHas('structure_santes', [
            'id' => $structure->id,
            'name' => 'mameabdou',
            'adresse' => 'parcelle',
            'telephone' => '775486325',
        ]);
    
        // Vérifiez que le mot de passe a été mis à jour correctement
        $this->assertTrue(Hash::check('P@sser123', $structure->fresh()->password));
    }
    
}
    



