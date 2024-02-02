<?php

namespace Database\Factories;

use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CampagneCollecteDon>
 */
class CampagneCollecteDonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CampagneCollecteDon::class;

    public function definition()
    {
        return [
            'jour' => $this->faker->randomElement(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']),
            'heure' => $this->faker->time(),
            'lieu' => $this->faker->address,
            'statut' => $this->faker->randomElement(['ouverte', 'complete']),
            'is_deleted' => false,
            'structure_id' => function () {
            
                return DB::table('structure_santes')->inRandomOrder()->first()->id;
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
