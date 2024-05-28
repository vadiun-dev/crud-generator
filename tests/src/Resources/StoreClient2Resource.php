<?php

namespace Src\Resources;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Src\Models\Client2;

class StoreClient2Resource extends Data
{
	public function __construct(
		public string $first_name,
	) {
	}


	public static function fromModel(Client2 $model): self
	{
		return new self(
		$model->first_name->toDateTimeString(),
		);
	}
}
