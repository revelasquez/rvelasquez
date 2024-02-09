<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Autor;

class AutorFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = Autor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Ludbin Guilberth', 'Kevin Torrez', 'Tania Payllo', 'Susana Ancasi', 'Eynar Torrez', 'Jhonny Chura']),
        ];
    }
}
