<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Support\Str;
use App\Models\StructureSante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StructureSanteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   


    public function definition()
    {
  

        return [
            'name' => $this->faker->unique()->company,
            'image' => $this->faker->imageUrl(),
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->unique()->phoneNumber,
            'role_id' => 1,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), 
            'is_blocked' => false,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
