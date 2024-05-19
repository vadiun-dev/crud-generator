<?php

namespace Src\Controllers;

use Src\Data\StoreCafeteraData;
use Src\Data\UpdateCafeteraData;
use Src\Models\Cafetera;
use Src\Resources\CafeteraResource;
use Src\Resources\DetailedCafeteraResource;

class CafeteraController
{
	public function store(StoreCafeteraData $data): void
	{
		$model = Cafetera::create([
		'category_id' => $data->name,
		'name' => $data->name,
		'description' => $data->name,
		'price' => $data->name,
		'fecha' => $data->name,
		'weight' => $data->name,
		]);
	}


	public function update(UpdateCafeteraData $data): void
	{
		$model = Cafetera::findOrFail($data->id);
		$model->update([
		'category_id' => $data->name,
		'name' => $data->name,
		'description' => $data->name,
		'price' => $data->name,
		'fecha' => $data->name,
		'weight' => $data->name,
		]);
	}


	public function destroy(int $id): void
	{
		Cafetera::destroy($id);
	}


	public function index()
	{
		return CafeteraResource::collection(Cafetera::all());
	}


	public function show(int $id)
	{
		return DetailedCafeteraResource::from(Cafetera::findOrFail($id));
	}
}
