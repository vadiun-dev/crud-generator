<?php

namespace Cafetera\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Models\Client;

class Cafetera extends Model
{
    use HasFactory;

    protected $table = 'cafeteras';

    protected $fillable = ['category_id', 'name', 'description', 'price', 'fecha', 'weight', 'client_id'];

    protected $casts = ['fecha' => 'datetime', 'weight' => 'bool'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
