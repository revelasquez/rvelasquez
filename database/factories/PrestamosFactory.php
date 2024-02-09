<?php

namespace Database\Factories;

use App\Models\Prestamos;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrestamosFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = Prestamos::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'libro_id' => $this->faker->numberBetween(1, 500),
            'cliente_id' => $this->faker->numberBetween(1, 6),
            'fecha_prestamo' => $this->faker->dateTimeBetween('-3 years', '-1 week'),
            'dias_prestamo' => $this->faker->numberBetween(1, 30),
            'estado' => $this->faker->randomElement(['En Prestamo', 'Devuelto']),
        ];
    }
}
