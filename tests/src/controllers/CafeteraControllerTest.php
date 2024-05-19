<?php

namespace Src\Controllers;

use Src\Models\Cafetera;
use Tests\TestCase;

class CafeteraControllerTest extends TestCase
{
    public function it_store_a_new_model(): void
    {
        $data = [
            'category_id' => $this->faker->randomNumber(5),
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'fecha' => $this->faker->dateTime,
            'weight' => $this->faker->boolean,
        ];
        $this->post(action([CafeteraController::class, 'store']), $data)->assertOk();
        $this->assertDatabaseHas(Cafetera::class, [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'fecha' => $data['fecha'],
            'weight' => $data['weight'],
        ]);
    }
}
