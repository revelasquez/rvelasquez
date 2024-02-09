<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Sergio Ampuero', 'Gustavo Sanchez', 'Juan Jose Silva', 'Orlando Ojeda', 'Mauricio Orellana', 'Rosario Mamani']),
            'email' => $this->faker->unique()->safeEmail,
            'celular' => $this->faker->unique()->numberBetween(70000000, 79900000),
        ];
    }
}
