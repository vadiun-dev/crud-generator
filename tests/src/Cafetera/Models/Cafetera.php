<?php

namespace Src\Cafetera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafetera extends Model
{
	use HasFactory;

	protected $table = 'cafeteras';
	protected $fillable = ['category_id', 'name', 'description', 'price', 'fecha', 'weight'];
	protected $casts = ['fecha' => 'datetime', 'weight' => 'bool'];
}
