<?php

namespace Src\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CafeteraData extends Data
{
	public int $category_id;
	public ?string $name;
	public string $description;
	public float $price;
	public Carbon $fecha;
	public bool $weight;
}
