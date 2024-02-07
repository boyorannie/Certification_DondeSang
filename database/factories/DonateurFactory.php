<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Donateur;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donateur>
 */
class DonateurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition()
    {



        return [
            'name' => $this->faker->firstName,
            'prenom' => $this->faker->lastName,
            'adresse' => $this->faker->address,
            'image' => $this->faker->imageUrl(),
            'sexe' => $this->faker->randomElement(['Femme', 'Homme']),
            'telephone' => $this->faker->unique()->phoneNumber,
            'cni' => $this->faker->unique()->randomNumber(8),
            'groupe_sanguin' => $this->faker->randomElement(['O+', 'O-', 'B-', 'B+', 'A-', 'A+', 'AB-', 'AB+']),
            'role_id' => 2,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('Password@123'),
            'is_blocked' => false,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
