<?php

namespace Src\Controllers\Controllers;

use Client;
use Src\Controllers\Data\StoreClientData;
use Src\Controllers\Data\UpdateClientData;
use Src\Controllers\Resources\ClientResource;
use Src\Controllers\Resources\DetailedClientResource;

class ClientController
{
	public function store(StoreClientData $data): void
	{
		$model = Client::create([
		'first_name' => $data->name,
		]);
	}


	public function update(UpdateClientData $data): void
	{
		$model = Client::findOrFail($data->id);
		$model->update([
		'first_name' => $data->name,
		]);
	}


	public function destroy(int $id): void
	{
		Client::destroy($id);
	}


	public function index()
	{
		return ClientResource::collection(Client::all());
	}


	public function show(int $id)
	{
		return DetailedClientResource::from(Client::findOrFail($id));
	}
}
