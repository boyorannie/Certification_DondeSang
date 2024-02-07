<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Donateur;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonateurTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testInscriptionDonateur()
    {
        $donateur = Donateur::factory()->create();
    
        $token = Auth::guard('donateur')->login($donateur);

        $this->assertNotNull($token);
    
        $donnees = [
            'name' => 'John',
            'prenom' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'MotDePasseSecret@123',
            'adresse' => '123 rue Principale',
            'cni' => '1234567891234',
            'groupe_sanguin' => 'A+',
            'sexe' => 'Homme',
            'telephone' => '789058754',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ];
    
        Storage::fake('public');
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/InscriptionDonneur', $donnees);
    
    }
    
  

    public function testLoginDonateur()
    {
        $password = 'Motdepasse@123';
        
        $donateur = Donateur::factory()->create([
            'role_id' => 2,
            'email' => 'donateur@example.com',
            'password' => Hash::make($password),
        ]);
   
        $donnees = [
            'email' => $donateur->email,
            'password' => $password, 
        ];
        

        $response = $this->json('POST', '/api/loginDonateur', $donnees);
    //dd($response);
        $response->assertStatus(200)
       ->assertJson([
        'status' => true,
        'message' =>$response->json('message'),
        'token' => $response->json('token'),
        'donateur' => $response->json('donateur'),
    ]);
}

public function testLogoutDonateur()
    {
        $donateur = Donateur::factory()->create([
            'role_id' => 2,
        ]);

        $token = JWTAuth::fromUser($donateur);

        // Connexion de l'utilisateur avec le guard 'donateur' en utilisant le token JWT
        $this->withHeader('Authorization', 'Bearer ' . $token);
       
        $response = $this->json('GET', '/api/logoutDonateur');
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' =>$response->json('message'),
            ]);

     // vérifie si le donateur s'est bien déconnecté
        $this->assertGuest('donateur');
    }
    public function testModifierDonateur()
{
    $donateur = Donateur::factory()->create();

    $response = $this->actingAs($donateur, 'donateur')
        ->json('POST', "api/modifierCompte/$donateur->id", [
            'name' => 'Samb',
            'prenom' =>'Abdou',
            'adresse' => 'parcelle',
            'telephone' => '775486328',
            'cni' => '1247895632874',
            'sexe' => 'Femme',
            'groupe_sanguin' => 'O+',
            'image' => UploadedFile::fake()->image('donateur.jpg'),
            'password' => 'P@sser1234',
        ]);

     $response->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => $response->json('data'),
        ]);

    // Vérifie que le mot de passe a été mis à jour correctement
    $this->assertTrue(Hash::check('P@sser1234', $donateur->fresh()->password));
}


public function testListeDonateur()
{
    $donateurs = Donateur::factory(3)->create();
    $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
    $this->actingAs($admin, 'donateur');
    $response = $this->get('api/listeDonateur');
    $response->assertStatus(200);
    
    // vérifie que la réponse contient les structures créées
    foreach ($donateurs as $donateur) {
        $response->assertJsonFragment([
            'name' => $donateur->name,
            'adresse' => $donateur->adresse,
            'telephone' => $donateur->telephone,
            'prenom' => $donateur->prenom,
            'groupe_sanguin' => $donateur->groupe_sanguin,
            'cni' => $donateur->cni,
            'sexe' => $donateur->sexe,
            'image' => $donateur->image,
        ]);
    }
    
}

public function testBloquerDonateur()
{

    $donateur = Donateur::factory()->create();
    $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
    $this->actingAs($admin, 'structure');

    $response = $this->putJson("api/bloquerDonateur/$donateur->id");

    $response->assertStatus(200);
 
    $this->assertDatabaseHas('donateurs', [
        'id' => $donateur->id,
        'is_blocked' => true,
    ]);

    $response->assertJson([
        'status' => true,
        'message' => 'Le Donateur bloqué avec succès.',
    ]);
}


public function testafficherDonateursBloques()
{
    $donateurBloque1 = Donateur::factory()->create(['is_blocked' => true]);
    $donateurBloque2 = Donateur::factory()->create(['is_blocked' => true]);
    $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
    $this->actingAs($admin, 'donateur');

    $response = $this->getJson('/api/afficherDonateursBloques');
    $response->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'donateurs' => [
            '*' => ['id', 'is_blocked'],
        ],
    ]);
}



public function testAfficherDonateurNonBloques()
{
    $donateurNonBloque1 = Donateur::factory()->create(['is_blocked' => false]);
    $donateurNonBloque2 = Donateur::factory()->create(['is_blocked' => false]);
    $admin = User::where('email', 'khadijambengue96@gmail.com')->first();
    $this->actingAs($admin, 'donateur');

    $response = $this->getJson('/api/afficherDonateursNonBloques');
    $response->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'donateurs' => [
            '*' => ['id', 'is_blocked'],
        ],
    ]);
}
}
