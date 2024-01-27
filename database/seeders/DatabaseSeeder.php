<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\GroupeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       

          \App\Models\Role::factory(1)->create(['libelle' => 'admin']);
          \App\Models\Role::factory(1)->create(['libelle' => 'donneur']);
          \App\Models\Role::factory(1)->create(['libelle' => 'StructureSante']);
           User::factory(1)->create();
           
           
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
    
}
