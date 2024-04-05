<?php

namespace Src\Cafetera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafetera extends Model
{
    use HasFactory;

    protected $table = 'cafeteras';

    public $fillable = ['category_id', 'name', 'description', 'price', 'fecha', 'weight'];

    public $casts = ['fecha' => 'datetime', 'weight' => 'bool'];
}
