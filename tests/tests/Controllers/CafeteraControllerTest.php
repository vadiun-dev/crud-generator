<?php

namespace Tests\Controllers;

use Src\Controllers\CafeteraController;
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


	public function it_updates_a_model(): void
	{
		$model = Cafetera::factory()->create();

		$data = [
		'category_id' => $this->faker->randomNumber(5),
		'name' => $this->faker->word,
		'description' => $this->faker->word,
		'price' => $this->faker->randomFloat(2, 0, 100),
		'fecha' => $this->faker->dateTime,
		'weight' => $this->faker->boolean,
		];
		$this->put(action([CafeteraController::class, 'update'], $model->id), $data)->assertOk();
		$this->assertDatabaseHas(Cafetera::class, [
		'id' => $model->id,
		'category_id' => $data['category_id'],
		'name' => $data['name'],
		'description' => $data['description'],
		'price' => $data['price'],
		'fecha' => $data['fecha'],
		'weight' => $data['weight'],
		]);
	}


	public function it_deletes_a_model(): void
	{
		$model = Cafetera::factory()->create();

		$this->delete(action([CafeteraController::class, 'destroy'], $model->id))->assertOk();
		$this->assertDatabaseMissing(Cafetera::class, ['id' => $model->id]);
	}


	public function it_returns_a_collection_of_models(): void
	{
		$models = Cafetera::factory(1)->create();

		$this->get(action([CafeteraController::class, 'index']))->assertOk();
		$this->assertExactJson([
		[
		'category_id' => $models[0]->category_id,
		'name' => $models[0]->name,
		'description' => $models[0]->description,
		'price' => $models[0]->price,
		'fecha' => $models[0]->fecha,
		'weight' => $models[0]->weight,
		]]);
	}


	public function it_returns_a_model(): void
	{
		$model = Cafetera::factory()->create();

		$this->get(action([CafeteraController::class, 'show'], $model->id))->assertOk();
		$this->assertExactJson([
		'category_id' => $model->category_id,
		'name' => $model->name,
		'description' => $model->description,
		'price' => $model->price,
		'fecha' => $model->fecha,
		'weight' => $model->weight,
		]);
	}
}
