<?php

namespace Database\Factories;

use App\Models\Donateur;
use App\Models\PromesseDon;
use App\Models\CampagneCollecteDon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PromesseDon>
 */
class PromesseDonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PromesseDon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'campagne_id' => function () {
                return CampagneCollecteDon::factory();
            },
            'donateur_id' => function () {
                return Donateur::factory();
            },
            'statut' => 'en attente',
        ];
    }
}