<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Cafetera\Models\Cafetera;

class CafeteraFactory extends Factory
{
	protected $model = Cafetera::class;


	public function definition()
	{
		return [
		'category_id' => $this->faker->randomNumber(5),
		'name' => $this->faker->word,
		'description' => $this->faker->word,
		'price' => $this->faker->randomFloat(2, 0, 100),
		'fecha' => $this->faker->dateTime,
		'weight' => $this->faker->boolean,
		];
	}
}
